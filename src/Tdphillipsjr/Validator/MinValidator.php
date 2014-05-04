<?php

namespace Tdphillipsjr\Validator;

/**
 * Validator determines if the value is max is larger than the value in data.  This is interpreted
 * differently based on the type of data.
 *      $data = string: $min considers the length of the string.
 *      $data = number: $min considers the value of the number.]
 *      If max is an array, the first index is treated as a cast, so we would always compare the
 *          data as a string.
 */
class MinValidator extends BaseValidator
{
    private $_validationType;
    
    public function __construct($data, $min)
    {
        if (is_array($min)) {
            $this->_validationType = $min[0];
            $min = $min[1];
        } else {
            $this->_validationType = null;
        }
        parent::__construct($data, $min);
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
        if (strlen($this->_data) < $this->_validateAgainst) $this->addError('Failed validating that "' . $this->_data . 
                                                                            '" is at least ' . $this->_validateAgainst . 
                                                                            ' characters.');
    }
    
    public function validateNumber()
    {
        if ($this->_data < $this->_validateAgainst) $this->addError('Validation failed:  ' . $this->_data . 
                                                                    ' is less than minimum ' . $this->_validateAgainst . 
                                                                    '.');
    }
}
