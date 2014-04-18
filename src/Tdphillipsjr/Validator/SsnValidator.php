<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data is a validly formatted Social Security Number.
 */
class SsnValidator extends BaseValidator
{
    public function __construct($data)
    {
        parent::__construct($data, array());
    }
    
    /**
     * Validate this is a social security number which is one of the following formats:
     *  1) Nine digits
     *  2) Nine digits formatted like 123-45-6789
     *  3) Matches SSN rules: 
     *      1) Cannot be 219-09-9999
     *      2) Cannot be 078-05-1120
     *      3) Cannot start with 900 through 999
     *      4) Cannot start with 666
     *      5) Cannot contain all zeros in any group.
     *
     * Note that this DOES NOT use the SSN rules to validate that is a legal social security
     * number... just that it matches the format.
     *
     * @return boolean
     * @throws Validator\ValidationException
     */
    public function validate()
    {
        $pattern = '/^(?!219-09-9999|078-05-1120|219099999|078051120)(?!666|000|9\d{2})\d{3}-?(?!00)\d{2}-?(?!0{4})\d{4}$/';
        
        if (!preg_match($pattern, $this->_data)) $this->addError('Invalid social security entered.');

        return !sizeof($this->_errors);
    }
}
