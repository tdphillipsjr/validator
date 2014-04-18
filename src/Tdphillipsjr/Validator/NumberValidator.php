<?php

namespace Tdphillipsjr\Validator;

/**
 * Validate that $data is a number.
 */
class NumberValidator extends BaseValidator
{
    public function __construct($data)
    {
        parent::__construct($data, array());
    }
    
    public function validate()
    {
        if (!is_numeric($this->_data)) {
            $this->addError('Value must be a number.');
        }
        
        return !sizeof($this->_errors);
    }
}