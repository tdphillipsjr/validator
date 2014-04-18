<?php

namespace Tdphillipsjr\Validator;

/**
 * Validate that more than one fields adds up to exactly a specific value.  The primary use case here
 * would be multiple percentage fields that would need to add up to 100.
 */
class TotalValidator extends BaseValidator
{
    private $_additionalIndices = array();
    private $_additionalData    = array();
    
    public function __construct($data, $settings)
    {
        $compareValue = $settings[0];
        if (!is_numeric($compareValue)) {
            throw new DataException('Validator expects the comparison value to be a number.');
        }
        
        parent::__construct($data, $compareValue);

        $this->_additionalIndices = array_slice($settings, 1, sizeof($settings));
    }
    
    /**
     * Total the additional data with the loaded data and verify that aggregate total is equal to
     * the validateAgainst value.
     */
    public function validate()
    {
        $total = 0;
        foreach ($this->_additionalData as $value) {
            $total += $value;
        }
        $total += $this->_data;
        
        if ($total != $this->_validateAgainst) {
            $this->addError('Value combined with ' . implode(',', $this->_additionalIndices) . ' should total ' . $this->_validateAgainst . '.');
        }
        
        return !sizeof($this->_errors);
    }
    
   /**
    * Take the additional fields we want to total from the data array.
    */
    protected function _loadAdditionalData($settings)
    {
        foreach ($this->_additionalIndices as $index) {
            $value = isset($settings[$index]) ? $settings[$index] : 0;
            $this->_additionalData[$index] = $value;
        }
    }

}
