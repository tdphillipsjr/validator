<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data exists in an array of selections.
 */
class ChoiceValidator extends BaseValidator
{
    /**
     * Determine if $data is in $choices.
     *
     * @param   Mixed   $data       $data may be any single element or an array.  If it's an array, 
     *                                  validate every item in the array is valid.
     * @param   Array   $choices    The things to check against $data
     */
    public function __construct($data, $choices=array())
    {
        $input = is_array($data) ? $data : array($data);
        parent::__construct($input, $choices);
    }
    
    /**
     * Validate $data is in the $validateAgainst array.
     *
     * @return boolean
     * @throws Validator\ValidationException
     */
    public function validate()
    {
        foreach ($this->_data as $data) {
            if (!in_array($data, $this->_validateAgainst)) $this->addError('Value is not a valid selection.');
        }
    
        return !sizeof($this->_errors);
    }
}
