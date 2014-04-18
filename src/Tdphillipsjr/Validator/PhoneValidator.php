<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data is a valid phone number.
 */
class PhoneValidator extends BaseValidator
{
    public function __construct($data)
    {
        parent::__construct($data, array());
    }
    
    /**
     * Currently only matching American format phone numbers of ten digits.  Also validates the 
     * first number is not 1 or 0 and the second number is not 9.  This is NOT validating 
     * generally accepted formats and expects any non-numeric characters and a leading 1
     * to have been stripped out.
     *
     * @return boolean
     * @throws Validator\ValidationException
     */
    public function validate()
    {
        $pattern = '/^[2-9][0-9]{9}$/';
        
        if (!preg_match($pattern, $this->_data)) {
            $this->addError('Phone number did not match an expected format: 10 digits, not starting with 1 or 0.');
        }

        return !sizeof($this->_errors);
    }
}
