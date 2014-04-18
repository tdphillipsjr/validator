<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data is a validly formatted Tax ID number
 */
class TinValidator extends BaseValidator
{
    public function __construct($data)
    {
        parent::__construct($data, array());
    }
    
    /**
     * Validate this is a tax id number which is one of the following formats:
     *  1) Nine digits
     *  2) Nine digits formatted like 12-3456789
     *
     * @return boolean
     * @throws Validator\ValidationException
     */
    public function validate()
    {
        $pattern = '/^[0-9]{2}-?[0-9]{7}$/';
        
        if (!preg_match($pattern, $this->_data)) $this->addError('Invalid Tax ID/EIN number entered.');

        return !sizeof($this->_errors);
    }
}
