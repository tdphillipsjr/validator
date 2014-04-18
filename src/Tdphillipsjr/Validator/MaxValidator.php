<?php

namespace Tdphillipsjr\Validator;

/**
 * Validator determines if the value is max is larger than the value in data.  This is interpreted
 * differently based on the type of data.
 *      $data = string: $max considers the length of the string.
 *      $data = number: $max considers the value of the number
 */
class MaxValidator extends BaseValidator
{
    public function __construct($data, $max)
    {
        $max = is_array($max) ? $max[0] : $max;
        parent::__construct($data, $max);
    }
    
    public function validate()
    {
        if (is_numeric($this->_data)) {
            if ($this->_data > $this->_validateAgainst) $this->addError('Numeric value too large. Maximum is ' . $this->_validateAgainst);
        
        } else if (is_string($this->_data)) {
            if (strlen($this->_data) > $this->_validateAgainst) $this->addError('Entry must be shorter than ' . $this->_validateAgainst . ' characters.');
        }
        
        return !sizeof($this->_errors);
    }
}
