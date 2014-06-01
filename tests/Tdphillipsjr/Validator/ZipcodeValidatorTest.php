<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\ZipcodeValidator;

class ZipcodeValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testFiveDigitPass()
    {
        $data = 12345;
        $validator = new ZipcodeValidator($data);
        $this->assertTrue($validator->validate());
    }
    
    public function testNineDigitPass()
    {
        $data = 123456789;
        $validator = new ZipcodeValidator($data);
        $this->assertTrue($validator->validate());
    }
    
    public function testNineDigitHyphenPass()
    {
        $data = '12345-6789';
        $validator = new ZipcodeValidator($data);
        $this->assertTrue($validator->validate());
    }
    
    public function testShortFail()
    {
        $data = 1234;
        $validator = new ZipcodeValidator($data);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testLetterFail()
    {
        $data = '12c45';
        $validator = new ZipcodeValidator($data);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testBadSymfolFail()
    {
        $data = '12345&6789';
        $validator = new ZipcodeValidator($data);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
}
?>