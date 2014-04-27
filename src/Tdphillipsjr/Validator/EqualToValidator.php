<?php

namespace Tdphillipsjr\Validator;

/**
 * Validate data is equal to something.
 */
class EqualToValidator extends BaseValidator
{
    public function __construct($data, $equals)
    {
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
