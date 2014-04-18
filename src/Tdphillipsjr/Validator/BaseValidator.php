<?php

namespace Tdphillipsjr\Validator;

class ValidationException extends \Exception {}
class DataException       extends \Exception {}

abstract class BaseValidator
{
    protected $_data;
    protected $_validateAgainst;
    protected $_errors= array();
    protected $_throw = false;
    
    /**
     * This constructor should be called from every child class.  This sets the data
     * that is being validated and the data being validated against.
     *
     * @param   Mixed   $data               The user entered data being validated.
     * @param   Mixed   $validateAgainst    The value or values we are validating against.
     */
    public function __construct($data, $validateAgainst)
    {
        $this->_data            = $data;
        $this->_validateAgainst = $validateAgainst;
    }
    
    /**
     * If throw is active, the validator will throw a ValidationException when it adds an error
     *
     * @param   $throw  boolean
     */
    public function setThrow($throw)
    {
        $this->_throw = $throw;
    }
    
    /**
     * This adds a validation error to the error array and conditionally throws it.
     */
    protected function addError($data)
    {
        if ($this->_throw) throw new ValidationException($data);
        $this->_errors[] = $data;
    }
    
    /**
     * Retrieve all the errors
     */
    public function getErrors()
    {
        return $this->_errors;
    }
    
    /**
     * A protected method called _loadAddtionalData may or may not exist within a child class.  This
     * is used if some additional data must be loaded to the validator from the data array.  If the
     * function is not defined, nothing happens.
     *
     * @param   Mixed   $data   some additional data we want loaded to the validator.
     * @return  void.
     */
    public function loadAdditionalData($data=null)
    {
        if (method_exists($this, '_loadAdditionalData')) {
            $this->_loadAdditionalData($data);
        }
    }
    
    abstract public function validate();
}
