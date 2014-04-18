<?php

namespace Tdphillipsjr\Validator;

class StateValidator extends BaseValidator
{
    private $_includeTerritories = false;
    
    public $states = array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL',
                           'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 
                           'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 
                           'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 
                           'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY');
                           
    public $territories = array('AS','GU','MP','PR','VI','FM','MH','PW','AA','AE','AP');
    
    public function __construct($data, $withTerritories=false)
    {
        $this->_includeTerritories = $withTerritories;
        parent::__construct($data, array());
    }

    public function validate()
    {
        if ($this->_includeTerritories) {
            $validateArray = array_merge($this->states, $this->territories);
        } else {
            $validateArray = $this->states;
        }
        
        if (!in_array($this->_data, $validateArray)) $this->addError('Invalid state selected.');
        return !sizeof($this->_errors);
    }
}
