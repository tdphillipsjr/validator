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

    public function testMinValidatorArrayPass()
    {
        $validator = new MinValidator(array(21,22,15), 15);
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

    public function testMinValidatorPassesStringArray()
    {
        $validator = new MinValidator(array('test', 'this', 'stuff'), 3);
        $this->assertTrue($validator->validate());
    }
    
    public function testMinValidatorNumberFailsWithoutException()
    {
        $validator = new MinValidator(10, 15);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $expectedMessage = array('Numeric value too small. Minimum is 15');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Numeric value too small. Minimum is 10
     */
    public function testMinValidatorNumberFailsWithException()
    {
        $validator = new MinValidator(10, 15);
        $validator->setThrow(true);
        $validator->validate();
    }

    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Numeric value too large. Minimum is 15
     */
    public function testMinValidatorNumFailsArray()
    {
        $validator = new MinValidator(array(16,1,2), 15);
        $validator->setThrow(true);
        $validator->validate();
    }
    
    public function testMinValidatorStringFailsWithoutException()
    {
        $validator = new MinValidator('this is a string', 17);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $expectedMessage = array('Entry must be longer than 17 characters.');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }

    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Entry must be longer than 17 characters.
     */
    public function testMinValidatorStringFailsWithException()
    {
        $validator = new MinValidator('this is a string', 17);
        $validator->setThrow(true);
        $validator->validate();
    }

    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Entry must be longer than 6 characters.
     */
    public function testMinValidatorStringFailsWithExceptionArray()
    {
        $validator = new MinValidator(array('test', 'stuff', 'this', 'nonsense'), 6);
        $validator->setThrow(true);
        $validator->validate();
    }
}

?>