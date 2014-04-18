<?php

namespace Tdphillipsjr\Validator;

/**
 * Validator determines if the value is max is larger than the value in data.  This is interpreted
 * differently based on the type of data.
 *      $data = string: $min considers the length of the string.
 *      $data = number: $min considers the value of the number
 */
class MinValidator extends BaseValidator
{
    public function __construct($data, $min)
    {
        $min = is_array($min) ? $min[0] : $min;
        parent::__construct($data, $min);
    }
    
    public function validate()
    {
        if (is_numeric($this->_data)) {
            if ($this->_data < $this->_validateAgainst) $this->addError('Numeric value too small. Minimum is ' . $this->_validateAgainst);
        
        } else if (is_string($this->_data)) {
            if (strlen($this->_data) < $this->_validateAgainst) $this->addError('Entry must be longer than ' . $this->_validateAgainst . ' characters.');
        }
        
        return !sizeof($this->_errors);
    }
}
