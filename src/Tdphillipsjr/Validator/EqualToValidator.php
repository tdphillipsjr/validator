<?php

namespace Tdphillipsjr\Validator;

/**
 * Validate data is equal to something.
 */
class EqualToValidator extends BaseValidator
{
    private $_compareField = null;
    private $_compareValue = null;
    
    public function __construct($data, $equals)
    {
        if (is_array($equals)) {
            $this->_compareValue = $equals;
        } else {
            if (preg_match('/field-(.*)/', $equals, $matches)) {
                $this->_compareField = $matches[1];
            } else {
                $this->_compareValue = $equals;
            }
        }
            
        parent::__construct($data, null);
    }
    
    public function validate()
    {
        if ($this->_data != $this->_compareValue) {
            if ($this->_compareField) {
                $this->addError($this->_data . ' does not match value contained in ' . $this->_compareField);
            } else {
                $this->addError($this->_data . ' is required to be equal to ' . $this->_compareValue);
            }
        }
        
        return !sizeof($this->_errors);
    }
    
    /**
     * If compareField was defined, get the field.  If compareField was defined and the field
     * does not exist in $data, throw an exception.  If compareField was not defined, ignore this.
     */
    protected function _loadAdditionalData($data)
    {
        if ($this->_compareField) {
            if (array_key_exists($this->_compareField, $data)) {
                $this->_compareValue = $data[$this->_compareField];
                return;
            }
            
            throw new DataException('Running an "equal to field" comparison on a field that does not exist');
        }
    }
}
