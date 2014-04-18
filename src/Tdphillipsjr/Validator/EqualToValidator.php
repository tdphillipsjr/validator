<?php

namespace Tdphillipsjr\Validator;

/**
 * Validate data is equal to something.
 */
class EqualToValidator extends BaseValidator
{
    public function __construct($data, $equals)
    {
        /**
         * If both arguments are arrays, assume we want to compare arrays.  if only $equals is an 
         * array, strip it in to a value
         */
        if (is_array($equals) && ! is_array($data)) {
            $equals = $equals[0];
        }
        parent::__construct($data, $equals);
    }
    
    public function validate()
    {
        if ($this->_data != $this->_validateAgainst)
        {
            $this->addError($this->_data . ' is required to be equal to ' . $this->_validateAgainst);
        }
        
        return !sizeof($this->_errors);
    }
}
