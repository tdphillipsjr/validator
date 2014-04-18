<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\TotalValidator;

class TotalValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->totals = array('one'     => 25,
                              'two'     => 25,
                              'three'   => 25,
                              'four'    => 25);
    }
    
    public function testTotalPasses()
    {
        $inputArray = array(100, 'two', 'three', 'four');
        
        $validator = new TotalValidator(25, $inputArray);
        $validator->loadAdditionalData($this->totals);
        $this->assertTrue($validator->validate());
    }
    
    public function testTotalPassesWithExtraData()
    {
        $inputArray = array(100, 'two', 'three', 'four');
        $this->totals['five'] = 100;
        $this->totals['six']  = 'tommy';
        
        $validator = new TotalValidator(25, $inputArray);
        $validator->loadAdditionalData($this->totals);
        $this->assertTrue($validator->validate());
    }
    
    public function testTotalFails()
    {
        $inputArray = array(125, 'two', 'three', 'four');
        
        $validator = new TotalValidator(25, $inputArray);
        $validator->loadAdditionalData($this->totals);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testTotalFailsTwo()
    {
        $inputArray = array(100, 'two', 'three', 'five');
        
        $validator = new TotalValidator(25, $inputArray);
        $validator->loadAdditionalData($this->totals);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage Validator expects the comparison value to be a number
     */
    public function testTotalValidatorNonNumberDataFails()
    {
        $inputArray = array('two', 'three');
        $validator = new TotalValidator(25, $inputArray);
    }
}

?>