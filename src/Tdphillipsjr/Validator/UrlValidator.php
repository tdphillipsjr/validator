<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data is a valid url.
 */
class UrlValidator extends BaseValidator
{
    public function __construct($data)
    {
        parent::__construct($data, array());
    }
    
    /**
     * This is not an RFC exhaustive validation, but it should match the most commonly
     * used url formats. 
     *
     * @return boolean
     * @throws Validator\ValidationException
     */
    public function validate()
    {
        $pattern = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        
        if (!preg_match($pattern, $this->_data)) $this->addError('URL does not match expected format.');

        return !sizeof($this->_errors);
    }
}
