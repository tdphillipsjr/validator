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
        $expectedMessage = array('Numeric value too large. Maximum is 10');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Numeric value too large. Maximum is 10
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
        $expectedMessage = array('Entry must be shorter than 15 characters.');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }

    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Entry must be shorter than 15 characters.
     */
    public function testMaxValidatorStringFailsWithException()
    {
        $validator = new MaxValidator('this is a string', 15);
        $validator->setThrow(true);
        $validator->validate();
    }
}

?>