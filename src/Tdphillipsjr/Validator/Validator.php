<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\Parser;

class ValidatorException extends \Exception
{
    public $errors = array();
    public function __construct($errors)
    {
        $this->errors = $errors;
    }
}

/**
 * This class is an overall validator that takes an assortment of data and a schema, parses through
 * the validation settings and applies the correct validation checks to the given data.
 */
class Validator
{
    /**
     * A hash array of data and values.  The generally expected form here would be the
     * tag title of an element as the key and the content of the element as the value.
     * for example:
     *  <line1>123 somewhere place</line1>
     *  <city>Brooklyn</city>
     * would translate to:
     *  array('line1' => '123 somewhere place',
     *        'city'  => 'Brooklyn');
     *
     * This could probably be used to validate database data as well.
     */
    private $_data;
    
    /**
     * The schema array language takes pipe-delimited requirements that we will translate in
     * to validators.  The keys of the schema array should match the keys of their data array.
     */
    private $_schema;
    
    /**
     * Validation errors.
     */
    private $_errors;
    
    /**
     * The parser that we use to parse through the validation language
     */
    private $_parser;
    
    /**
     * These validations are handled inline and not in validation classes
     */
    private $_exempt = array('required', 
                             'requiredIf', 
                             'notRequiredIf');
    
    public function __construct()
    {
        $this->_parser = new Parser();
    }
    
    public function loadSchema($schema)
    {
        $this->_schema = $schema;
    }
    
    public function loadData($data)
    {
        $this->_data = $data;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * This base validate function traverses the entire schema and tries to run each cell's validation against
     * each cell.  This will try to run each thing and compile a list of validation exceptions.  
     */
    public function validate()
    {
        foreach ($this->_schema as $index => $validationString) {
            $validations = $this->_parser->getValidations($validationString);
            $required    = false;
            
            /**
             * See if this is required.  If it is and it doesn't exist add an error and break
             * from this iteration.  Checking for additional validations is redundant.
             */
            if ($this->isRequired($validations)) {
                if ( ! $this->fieldExists($index)) {
                    $this->_errors[] = "$index is required" . $this->requiredBecause($validations);
                    continue;
                }
                $required = true;
            }

            /**
             * Now if there are any other validations, instantiate their validation class and 
             * attempt to validate the data in the data array.
             */
            foreach ($validations as $validation) {
                
                // Bypass required, we took care of that above the foreach loop.
                if ($this->_bypassValidation($validation)) continue;
                
                // If the value isn't required and the key doesn't exist (or is empty), we don't need to fail.
                if (!$required && !$this->fieldExists($index)) continue;
                
                // Parse each validation cell in to the class and the parameters.
                $callData = $this->_parser->parseCall($validation);
                
                // If a class does not exist, this is not a recoverable error, so throw a parse exception and die
                if (!class_exists($callData['class'])) throw new ValidatorException($callData['class'] . ' class does not exist.');
                
                // Otherwise, try to instantiate the validator we're looking for
                if (isset($callData['parameters'])) {
                    $validator = new $callData['class']($this->_data[$index], $callData['parameters']);
                } else {
                    $validator = new $callData['class']($this->_data[$index]);
                }
                $validator->loadAdditionalData($this->_data);
                
                // And then run its validate() method
                try {
                    $validator->setThrow(true);
                    $validator->validate();
                } catch (ValidationException $e) {
                    $this->_errors[] = "$index: " . $e->getMessage();
                }
            }
        }
        if (sizeof($this->_errors)) throw new ValidatorException($this->_errors);
        return true;
    }
    
    /**
     * There are some methods that we bypass in validation because they are handled directly in 
     * validator.  These are stored in the _exempt variable.  This function handles figuring
     * out if we need to bypass a validation for whatever reason.
     *
     * @param void
     * @return boolean
     */
    private function _bypassValidation($validationString)
    {
        $validationArray = explode(':', $validationString);
        return in_array($validationArray[0], $this->_exempt);
    }
    
    /**
     * Given an index, check to see if it exists and has data.  We should let numeric
     * zero pass here since a field containing zero does contain something.  Null, false
     * and empty string should still fail.
     *
     * @param   string  $index      An index in the data array.
     * @return  boolean
     */
    public function fieldExists($index)
    {
        if (!isset($this->_data[$index])) {
            return false;
        } else {
            if (is_numeric($this->_data[$index])) {
                return true;
            } else {
                return !empty($this->_data[$index]);
            }
        }
    }
    
    /**
     * Given an array of validations, determine if the index is required.
     *
     * @param   string  $index          The index of the schema array to check.
     * @param   Array   $validations    An exploded validations string.
     * @return  boolean.
     */
    public function isRequired($validations)
    {
        // If "required" is one of the indexes, it is required.
        if (in_array('required', $validations))
        {
            return true;
        }
        
        // If "requiredIf" is one of the indexes and the data matches, it is required.
        else if ($this->_parser->hasValidationType('requiredIf', $validations))
        {
            $requiredIf     = $this->_parser->extractValidation('requiredIf', $validations);
            $requiredIfData = $this->_parser->parseParameters($requiredIf);
            if (!sizeof($requiredIfData['values']))
            {
                throw new ValidatorException("requiredIf with no values is not supported.");
            }
            
            // If the field exists and has one of the selected values, this is required.
            return $this->fieldExists($requiredIfData['index']) && 
                    in_array($this->_data[$requiredIfData['index']], $requiredIfData['values']);
        }
        
        // If "notRequiredIf" is one of the indexes and the data matches, it is not required
        else if ($this->_parser->hasValidationType('notRequiredIf', $validations))
        {
            $notRequiredIf     = $this->_parser->extractValidation('notRequiredIf', $validations);
            $notRequiredIfData = $this->_parser->parseParameters($notRequiredIf);
            if (!sizeof($notRequiredIfData['values'])) 
            {
                throw new ValidatorException("notRequiredIf with no values is not supported.");
            }
            
            // If the field exists, and the data is in the array, then this is NOT required.
            return ! ($this->fieldExists($notRequiredIfData['index']) && 
                    in_array($this->_data[$notRequiredIfData['index']], $notRequiredIfData['values']));
        }
        
        // If none of these cases were found, it is NOT required.
        else
        {
            return false;
        }
    }
    
    /**
     * There are 3 different ways we can call something required.  So this returns an addendum to
     * the error message if it needs it.
     *
     * @params  array   $validations    An exploded validation string.
     * @return  string
     */
    public function requiredBecause($validations)
    {
        // Plain required, no reason.
        if (in_array('required', $validations))
        {
            return '.';
        }
        else if ($this->_parser->hasValidationType('requiredIf', $validations))
        {
            $requiredIf     = $this->_parser->extractValidation('requiredIf', $validations);
            $requiredIfData = $this->_parser->parseParameters($requiredIf);
            return ' because ' . $requiredIfData['index'] . ' has one of the selected values.';
        }
        else if ($this->_parser->hasValidationType('notRequiredIf', $validations))
        {
            $notRequiredIf     = $this->_parser->extractValidation('notRequiredIf', $validations);
            $notRequiredIfData = $this->_parser->parseParameters($notRequiredIf);
            return ' because ' . $notRequiredIfData['index'] . ' does not contain an exempt value.';
        }
        
        return;
    }
}
