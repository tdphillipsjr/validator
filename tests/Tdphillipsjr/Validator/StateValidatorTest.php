<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\StateValidator;

class StateValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->states = array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL',
                              'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 
                              'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 
                              'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 
                              'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY');
                               
        $this->territories = array('AS','GU','MP','PR','VI','FM','MH','PW','AA','AE','AP');
    }
    
    public function testStateValidatorPasses()
    {
        foreach ($this->states as $state) {
            $validator = new StateValidator($state);
            $this->assertTrue($validator->validate());
        }
    }
    
    public function testStateValidatorTerritoriesFail()
    {
        foreach ($this->territories as $territory) {
            $validator = new StateValidator($territory);
            $validator->setThrow(false);
            $this->assertFalse($validator->validate());
        }
    }
    
    public function testStateValidatorTerritoriesPass()
    {
        foreach ($this->states as $state) {
            $validator = new StateValidator($state, true);
            $this->assertTrue($validator->validate());
        }

        foreach ($this->territories as $territory) {
            $validator = new StateValidator($territory, true);
            $this->assertTrue($validator->validate());
        }
    }
    
    public function testStateValidatorFailsBadData()
    {
        $validator = new StateValidator('test');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

}

?>