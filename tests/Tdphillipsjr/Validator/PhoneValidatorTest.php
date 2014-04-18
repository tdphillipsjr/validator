<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\PhoneValidator;

class PhoneValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testPhoneValidatorPasses()
    {
        $validator = new PhoneValidator(5185551212);
        
        $this->assertTrue($validator->validate());
    }
    
    /**
     * Test failing without exception
     */
    public function testPhoneValidatorFailsWithoutException()
    {
        $validator = new PhoneValidator('test');
        $validator->setThrow(false);
        
        $this->assertFalse($validator->validate());
    }

    /**
     * Test failing with exception
     * @expectedException Tdphillipsjr\Validator\ValidationException
     */
    public function testPhoneValidatorFails()
    {
        $validator = new PhoneValidator('test');
        $validator->setThrow(true);
        
        $validator->validate();
    }
    
    /**
     * Test the regex a few times.
     */
    public function testPhoneValidationFailsTooLong()
    {
        $validator = new PhoneValidator(51855512121);
        $validator->setThrow(false);
        
        $this->assertFalse($validator->validate());
    }
    
    public function testPhoneValidationFailsTooShort()
    {
        $validator = new PhoneValidator(518555121);
        $validator->setThrow(false);
        
        $this->assertFalse($validator->validate());
    }
    
    public function testPhoneValidationCantStartWithOne()
    {
        $validator = new PhoneValidator(1855512121);
        $validator->setThrow(false);
        
        $this->assertFalse($validator->validate());
    }
    
    public function testPhoneNumberFailsWithFormatting()
    {
        $validator = new PhoneValidator('518-555-1212');
        $validator->setThrow(false);
        
        $this->assertFalse($validator->validate());
    }
}

?>