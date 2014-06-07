<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data is a date formatted like the string entered.  In this case, "validateAgainst" is
 * php string date format, so we will validate if the entered data matches that format and is a valid date.
 */
class DateValidator extends BaseValidator
{
    public function __construct($data, $format=null)
    {
        $format = is_array($format) ? $format[0] : $format;
        parent::__construct($data, $format);
    }
    
    /**
     * Validate
     *  1) The date string entered is in the format given.
     *  2) The date string entered is a valid date.
     *
     * @return boolean
     * @throws Validator\ValidationException
     */
    public function validate()
    {
        // If the date is invalid, just bail since the format will also be wrong.
        try {
            $date = new \DateTime($this->_data);
        } catch (\Exception $e) {
            $this->addError('Invalid date entered.');
            return false;
        }
        
        // The format mask is not required, but if it's defined, check it.
        if ($this->_validateAgainst) {
            // Otherwise, check the format, by running it through date() and matching results
            $date  = \DateTime::createFromFormat($this->_validateAgainst, $this->_data);
            $check = $date->format($this->_validateAgainst);
            if ($check != $this->_data) $this->addError('Date is not in the expected format.');
        }

        return !sizeof($this->_errors);
    }
}
