<?php

namespace Tdphillipsjr\Validator;

/**
 * Validator determines if the value is max is larger than the value in data.  This is interpreted
 * differently based on the type of data.
 *      $data = string: $min considers the length of the string.
 *      $data = number: $min considers the value of the number
 *      $data = array(): $min be compared to each value; one failure will fail.
 */
class MinValidator extends BaseValidator
{
    public function __construct($data, $min)
    {
        parent::__construct($data, $min);
    }
    
    public function validate()
    {
        // If it's a scalar value, make it an array.
        $dataArray = !is_array($this->_data) ? array($this->_data) : $this->_data;

        // Do the comparisons
        foreach ($dataArray as $data) {
            if (is_numeric($data)) {
                if ($data < $this->_validateAgainst) $this->addError('Numeric value too small. Minimum is ' . $this->_validateAgainst);
            
            } else if (is_string($data)) {
                if (strlen($data) < $this->_validateAgainst) $this->addError('Entry must be longer than ' . $this->_validateAgainst . ' characters.');
            }
        }
        
        return !sizeof($this->_errors);
    }
}
