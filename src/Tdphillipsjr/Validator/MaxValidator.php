<?php

namespace Tdphillipsjr\Validator;

/**
 * Validator determines if the value is max is larger than the value in data.  This is interpreted
 * differently based on the type of data.
 *      $data = string: $max considers the length of the string.
 *      $data = number: $max considers the value of the number.
 *      If max is an array, the first index is used to tell us how to treat the data.  For instance,
 *          to treat a zip code as a character data instead of a five digit number.
 */
class MaxValidator extends BaseValidator
{
    public function __construct($data, $max)
    {
        if (is_array($max)) {
            $this->_validationType = $max[0];
            $max = $max[1];
        } else {
            $this->_validationType = null;
        }
        parent::__construct($data, $max);
    }
    
    public function validate()
    {
        if ($this->_validationType == 'string') {
            $this->validateString();
        } else if ($this->_validationType == 'number') {
            $this->validateNumber();
        } else {
            if (is_numeric($this->_data)) {
                $this->validateNumber();
            } else {
                $this->validateString();
            }
        }
        
        return !sizeof($this->_errors);
    }

    public function validateString()
    {
        if (strlen($this->_data) > $this->_validateAgainst) $this->addError('Failed validating that "' . $this->_data . 
                                                                            '" is no more than ' . $this->_validateAgainst . 
                                                                            ' characters.');
    }
    
    public function validateNumber()
    {
        if ($this->_data > $this->_validateAgainst) $this->addError('Validation failed: ' . $this->_data . 
                                                                    ' is larger than maximum ' . $this->_validateAgainst . 
                                                                    '.');
    }
}
