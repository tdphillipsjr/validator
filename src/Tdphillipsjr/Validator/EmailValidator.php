<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data is a valid e-mail address.
 */
class EmailValidator extends BaseValidator
{
    public function __construct($data)
    {
        parent::__construct($data, array());
    }
    
    /**
     * This is not an RFC exhaustive validation, but it should match the most commonly
     * used e-mail address formats.  This does not currently validate the entered domain
     * name but probably could.
     *
     * @return boolean
     * @throws Validator\ValidationException
     */
    public function validate()
    {
        $pattern = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        
        if (!preg_match($pattern, $this->_data)) $this->addError('E-mail does not match expected format.');

        return !sizeof($this->_errors);
    }
}
