<?php

namespace Tdphillipsjr\Validator;

class ParseException extends \Exception {}

class Parser
{
    /**
     * Different validations are separated by double pipe.
     */
    public function getValidations($validationString)
    {
        return explode('||', $validationString);
    }

    /**
     * The validation language is text only with two delimiters
     *  :   indicates there is data coming,
     *  ,   indicates the data is a list.
     * This function returns an array with the name of the class to be instantiated and
     * the data we should be passing to it.
     * The three cases are:
     *  param   $validationInfo = 'foo'
     *  return  $array('class' => '\__NAMESPACE__\FooValidator')
     *
     *  param   $validationInfo = 'fooBar:15'
     *  return  $array('class'      => '\__NAMESPACE__\FooBarValidator'
     *                 'parameters' => array(15))
     *
     *  param   $validationInfo = 'fooBar:15,16,17'
     *  return  $array('class'      => '\__NAMESPACE__\FooBarValidator'
     *                 'parameters' => array(15,16,17))
     *
     * @param   string      $validationInfo
     * @return  array('class', 'parameters');
     */
    public function parseCall($validationInfo)
    {
        $validationData             = explode(':', $validationInfo);
        $returnData['class']        = '\\' . __NAMESPACE__ . '\\' . ucfirst($validationData[0]) . 'Validator';
        
        /**
         * If there was a second index, that means there was a :, which mean parameters followed
         * so send back an array of the parameters.  If there is more than one parameter, they
         * are separated by commas.
         */
        if (sizeof($validationData) > 1) {
            $params = explode(',', $validationData[1]);
            $returnData['parameters'] = sizeof($params) > 1 ? $params : $params[0];
        }

        return $returnData;
    }
    
    /**
     * Given a full validation string, return the post-colon data expecting the first index to be 
     * an argument and the rest to be the data.  This is the case for checking things like requiredIf
     * where the first index is a field and the rest are possible values.  A single parameter will be
     * returned as an array for consistency.
     *
     * @param   string  $validationString       A full validation string formatted like 'foo:bar,1,2,3
     * @return  Array('index', 'value'          In the above example array('index' => bar, 'values' => array(1,2,3))
     */
    public function parseParameters($validationString)
    {
        if (!is_string($validationString)) throw new ParseException('parseParameters: Invalid input format.');

        $returnData = array();
        $dataArray  = explode(':', $validationString);
        
        if (sizeof($dataArray) == 1) {
            $returnData['index'] = '';
            $returnData['values'] = '';
        } else {
            $paramArray = explode(',', $dataArray[1]);
            $returnData['index']    = $paramArray[0];
            $returnData['values']   = array_slice($paramArray, 1, sizeof($paramArray));
        }
        
        return $returnData;
    }

    /**
     * Given an array of validation strings, extract one.  
     *  
     * @param   string  $validationType     One of the validation types.
     * @param   Array   $validations        An array of validations from which one will be extracted
     */
    public function extractValidation($validationType, $validations)
    {
        foreach ($validations as $validation) {
            $validationArray = explode(':', $validation);
            if ($validationArray[0] == $validationType) return $validation;
        }
        return false;
    }
    
    /**
     * Verify they given block of validations has the requested validation.
     *
     * @params  string  $validationType     A case-sensitive validation type name.
     * @param   Array   $validations        An array of validations
     * @return  Boolean
     * @throws  ParseException
     */
    public function hasValidationType($validationType, $validations)
    {
        if ( ! is_array($validations)) throw new ParseException('hasValidationType usage; second argument must be an array.');
        foreach ($validations as $validation) {
            $validationArray = explode(':', $validation);
            if ($validationArray[0] == $validationType) return true;
        }
        
        return false;
    }
    

}
