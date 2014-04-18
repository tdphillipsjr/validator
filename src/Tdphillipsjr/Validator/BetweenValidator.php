<?php

namespace Tdphillipsjr\Validator;

/**
 * Determine if $data is between a minimum and maximum value.  This is INCLUSIVE.
 *      $data = string: $max considers the length of the string.
 *      $data = number: $max considers the value of the number
 */
class BetweenValidator extends BaseValidator
{
    /**
     * This constructor may fail if the values aren't appropriate.  This constructor will fail if:
     *      1) Values is not an array.
     *      2) Values is an array but does not have exactly 2 elements.
     *      3) Either of the values do not contain a number.
     *      4) The first element (min) is greater than the second element (max).
     *  This throws a DataException which should not be recovered from.
     *
     * @params  Mixed   $data       A number or a string being checked.
     * @params  Array   $values     A two cell array containing the min value in index 0 and 
     *                                  the max value in index 1
     * @return void
     * @throws DataException
     */
    public function __construct($data, $values=array())
    {
        if (!is_array($values) || (is_array($values) && sizeof($values) != 2)) {
            throw new DataException('Input to between validator must be an array of two numbers.');
        }
        
        if (!is_numeric($values[0]) || !is_numeric($values[1])) {
            throw new DataException('Comparison values may only be numbers.');
        }
        
        if ($values[0] > $values[1]) {
            throw new DataException('The minimum value can not be larger than the maximum value.');
        }
        
        $compare = array('min' => $values[0],
                         'max' => $values[1]);
        parent::__construct($data, $compare);
    }
    
    /**
     * @return Boolean
     * @throws ValidationException
     */
    public function validate()
    {
        if (is_numeric($this->_data)) {
            if ($this->_data < $this->_validateAgainst['min'] || $this->_data > $this->_validateAgainst['max']) {
                $this->addError('Numeric value is not between ' . $this->_validateAgainst['min'] . 
                                ' and ' . $this->_validateAgainst['max'] . '.');
            }
                    
        } else if (is_string($this->_data)) {
            if (strlen($this->_data) < $this->_validateAgainst['min'] || strlen($this->_data) > $this->_validateAgainst['max']) {
                $this->addError('String length is not between ' . $this->_validateAgainst['min'] . 
                                ' and ' . $this->_validateAgainst['max'] . ' characters.');
            }
        }
        
        return !sizeof($this->_errors);
    }
}
