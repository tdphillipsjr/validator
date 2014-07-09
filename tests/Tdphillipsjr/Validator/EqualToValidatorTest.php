<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\EqualToValidator;

class EqualToValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testEqualToValidatorPassesNumber()
    {
        $validator = new EqualToValidator(10, 10);
        $this->assertTrue($validator->validate());
    }
    
    public function testEqualToValidatorPassesString()
    {
        $validator = new EqualToValidator('test', 'test');
        $this->assertTrue($validator->validate());
    }
    
    public function testEqualToValidatorPassesArraySingle()
    {
        $validator = new EqualToValidator(array(10), array(10));
        $this->assertTrue($validator->validate());
    }
    
    public function testEqualToValidatorPassesArrayMultiple()
    {
        $validator = new EqualToValidator(array(10,15,20), array(10,15,20));
        $this->assertTrue($validator->validate());
    }
    
    public function testEqualToFieldPasses()
    {
        $validator = new EqualToValidator('test', 'field-confirm_test');
        $validator->loadAdditionalData(array('foo' => 'bar', 'confirm_test' => 'test'));
        $this->assertTrue($validator->validate());
    }
    
    /**
     * Assert that a scalar value is not equal to an array with a single index containing
     * the same value. This tests the previous state of the validator.
     */
    public function testNotEqualToValidatorFromValidator()
    {
        $validator = new EqualToValidator(10, array(10));
        $this->assertFalse($validator->validate());
    }
    
    public function testEqualToValidatorFailsWithoutException()
    {
        $validator = new EqualToValidator(15, 10);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $expectedMessage = array('15 is required to be equal to 10');
        $this->assertEquals($validator->getErrors(), $expectedMessage);
    }

    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedExceptionMessage 15 is required to be equal to 10
     */
    public function testEqualToValidatorNumberFailsWithException()
    {
        $validator = new EqualToValidator(15, 10);
        $validator->setThrow(true);
        $validator->validate();
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedExceptionMessage Running an "equal to field" comparison on a field that does not exist
     */
    public function testEqualToValidatorFailsFieldDoesntExist()
    {
        $validator = new EqualToValidator('test', 'field-confirm_test');
        $validator->loadAdditionalData(array('foo' => 'bar', 'confirmtest' => 'test'));
    }
    
    public function testEqualToValidatorFailsNoMatch()
    {
        $validator = new EqualToValidator('test', 'field-confirm_test');
        $validator->loadAdditionalData(array('foo' => 'bar', 'confirm_test' => 'testnomatch'));
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedExceptionMessage test does not match value contained in confirm_test
     */
    public function testEqualToValidatorFailsNoMatchWithException()
    {
        $validator = new EqualToValidator('test', 'field-confirm_test');
        $validator->loadAdditionalData(array('foo' => 'bar', 'confirm_test' => 'testnomatch'));
        $validator->setThrow(true);
        $validator->validate();
    }
}

?>