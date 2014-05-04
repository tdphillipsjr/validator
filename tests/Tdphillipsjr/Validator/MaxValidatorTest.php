<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\MaxValidator;

class MaxValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testMaxValidatorPassesNumberLessThan()
    {
        $validator = new MaxValidator(10, 15);
        $this->assertTrue($validator->validate());
    }
    
    public function testMaxValidatorPassesNumberEquals()
    {
        $validator = new MaxValidator(15, 15);
        $this->assertTrue($validator->validate());
    }
    
    public function testMaxValidatorWithStringCast()
    {
        $validator = new MaxValidator(12345, 15);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $validator = new MaxValidator(12345, array('string', 15));
        $this->assertTrue($validator->validate());
    }
    
    public function testMaxValidatorPassesStringLessThan()
    {
        $validator = new MaxValidator('this is a string', 17);
        $this->assertTrue($validator->validate());
    }
    
    public function testMaxValidatorPassesStringEquals()
    {
        $validator = new MaxValidator('this is a string', 16);
        $this->assertTrue($validator->validate());
    }
    
    public function testMaxValidatorNumberFailsWithoutException()
    {
        $validator = new MaxValidator(15, 10);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $expectedMessage = array('Validation failed: 15 is larger than maximum 10.');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedExceptionMessage Validation failed: 15 is larger than maximum 10.
     */
    public function testMaxValidatorNumberFailsWithException()
    {
        $validator = new MaxValidator(15, 10);
        $validator->setThrow(true);
        $validator->validate();
    }
    
    public function testMaxValidatorStringFailsWithoutException()
    {
        $validator = new MaxValidator('this is a string', 15);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $expectedMessage = array('Failed validating that "this is a string" is no more than 15 characters.');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }

    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedExceptionMessage Failed validating that "this is a string" is no more than 15 characters.
     */
    public function testMaxValidatorStringFailsWithException()
    {
        $validator = new MaxValidator('this is a string', 15);
        $validator->setThrow(true);
        $validator->validate();
    }
}

?>