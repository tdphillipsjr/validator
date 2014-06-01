<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data is a valid zipcode.  This only validates US zipcodes.
 * Zipcodes may be 5 digits, 9 digits, or 9 digits with a hyphen.
 */
class ZipcodeValidator extends BaseValidator
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
        $pattern = '/^\d{5}([\-]?\d{4})?$/';
        if (!preg_match($pattern, $this->_data)) $this->addError('Zip code is not in a valid US format.');

        return !sizeof($this->_errors);
    }
}
