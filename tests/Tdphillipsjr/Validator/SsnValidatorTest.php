<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\SsnValidator;

class SsnValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testSSNIsValidDash()
    {
        $testNum = '123-45-6789';
        $validator = new SsnValidator($testNum);
        $this->assertTrue($validator->validate());
    }
    
    public function testSSNIsValidNoDash()
    {
        $testNum = '123456789';
        $validator = new SsnValidator($testNum);
        $this->assertTrue($validator->validate());
    }
    
    public function testSSNIsInvalidBadFormat()
    {
        $testNum = '123.45.6789';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadDashFormat()
    {
        $testNum = '123-456-789';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNInvalidTooLong()
    {
        $testNum = '123-45-67890';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNInvalidTooShort()
    {
        $testNum = '123-45-678';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadNumberOne()
    {
        $testNum = '219-09-9999';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadNumberTwo()
    {
        $testNum = '219099999';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadNumberThree()
    {
        $testNum = '078-05-1120';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadNumberFour()
    {
        $testNum = '078051120';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadNumberDevil()
    {
        $testNum = '666-75-1120';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadNumberZeroesFirst()
    {
        $testNum = '000-75-1120';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadNumberZeroesSecond()
    {
        $testNum = '078-00-1120';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testSSNIsInvalidBadNumberZeroesThird()
    {
        $testNum = '078-75-0000';
        $validator = new SsnValidator($testNum);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testSSNIsInvalidBadNumberZeroesNines()
    {
        $suffix = '-45-6789';
        for ($i=900; $i<=999; $i++) {
            $testNum = $i . $suffix;
            $validator = new SsnValidator($testNum);
            $validator->setThrow(false);
            $this->assertFalse($validator->validate());
        }
    }
}
?>