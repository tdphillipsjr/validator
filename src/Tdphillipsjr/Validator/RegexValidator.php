<?php

namespace Tdphillipsjr\Validator;

class RegexValidator extends BaseValidator
{
    public function __construct($data, $regexPattern)
    {
        /**
         * Validator always passes an array of arguments.  It also explodes multiple arguments
         * at commas.  So if this was called from Validator, we either have a single index
         * array with a regex in it, or many pieces of a regex that need to be imploded on 
         * commas.  Usage with just a regex should also work fine.
         */
        $pattern = is_array($regexPattern) ? implode(',', $regexPattern) : $regexPattern;
        parent::__construct($data, $pattern);
    }

    public function validate()
    {
        if (!preg_match($this->_validateAgainst, $this->_data)) {
            $this->addError('Input is not in the required format.');
        }
        
        return !sizeof($this->_errors);
    }
}
