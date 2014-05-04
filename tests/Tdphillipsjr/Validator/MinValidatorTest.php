<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\MinValidator;

class MinValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testMinValidatorPassesNumberLessThan()
    {
        $validator = new MinValidator(15, 10);
        $this->assertTrue($validator->validate());
    }
    
    public function testMinValidatorPassesNumberEquals()
    {
        $validator = new MinValidator(15, 15);
        $this->assertTrue($validator->validate());
    }

    public function testMinValidatorPassesStringLessThan()
    {
        $validator = new MinValidator('this is a string', 15);
        $this->assertTrue($validator->validate());
    }
    
    public function testMinValidatorPassesStringEquals()
    {
        $validator = new MinValidator('this is a string', 16);
        $this->assertTrue($validator->validate());
    }

    public function testMinValidatorNumberFailsWithoutException()
    {
        $validator = new MinValidator(10, 15);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $expectedMessage = array('Validation failed:  10 is less than minimum 15.');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }

    public function testMinValidatorCastStringPass()
    {
        $validator = new MinValidator(0, 1);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $validator = new MinValidator(0, array('string', 1));
        $this->assertTrue($validator->validate());
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedExceptionMessage Validation failed:  10 is less than minimum 15.
     */
    public function testMinValidatorNumberFailsWithException()
    {
        $validator = new MinValidator(10, 15);
        $validator->setThrow(true);
        $validator->validate();
    }

    public function testMinValidatorStringFailsWithoutException()
    {
        $validator = new MinValidator('this is a string', 17);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $expectedMessage = array('Failed validating that "this is a string" is at least 17 characters.');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }

    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedExceptionMessage Failed validating that "this is a string" is at least 17 characters.
     */
    public function testMinValidatorStringFailsWithException()
    {
        $validator = new MinValidator('this is a string', 17);
        $validator->setThrow(true);
        $validator->validate();
    }
}

?>