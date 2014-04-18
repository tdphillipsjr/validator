<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\TinValidator;

class TinValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testTinIsValidDash()
    {
        $testNum = '12-3456789';
        $validator = new TinValidator($testNum);
        $this->assertTrue($validator->validate());
    }
    
    public function testTinIsValidNoDash()
    {
        $testNum = '123456789';
        $validator = new TinValidator($testNum);
        $this->assertTrue($validator->validate());
    }
    
    public function testTinIsInvalidBadFormat()
    {
        $testNum = '12.3456789';
        $validator = new TinValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testTinIsInvalidBadDashFormat()
    {
        $testNum = '123-456789';
        $validator = new TinValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testTinInvalidTooLong()
    {
        $testNum = '12-34567890';
        $validator = new TinValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testTinInvalidTooShort()
    {
        $testNum = '12-345678';
        $validator = new TinValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
}
?>