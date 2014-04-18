<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\BetweenValidator;

class BetweenValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testBetweenValidatorPassesNumber()
    {
        $validator = new BetweenValidator(10, array(5,15));
        $this->assertTrue($validator->validate());
    }
    
    public function testBetweenValidatorPassesNumberInclusiveLow()
    {
        $validator = new BetweenValidator(5, array(5,15));
        $this->assertTrue($validator->validate());
    }
    
    public function testBetweenValidatorPassesNumberInclusiveHigh()
    {
        $validator = new BetweenValidator(15, array(5,15));
        $this->assertTrue($validator->validate());
    }
    
    public function testBetweenValidatorFailsNumberLow()
    {
        $validator = new BetweenValidator(4, array(5,15));
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('Numeric value is not between 5 and 15.'));;
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Numeric value is not between 5 and 15.
     */
    public function testBetweenValidatorFailsNumberLowException()
    {
        $validator = new BetweenValidator(4, array(5,15));
        $validator->setThrow(true);
        $validator->validate();
    }
    
    public function testBetweenValidatorFailsNumberHigh()
    {
        $validator = new BetweenValidator(16, array(5,15));
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('Numeric value is not between 5 and 15.'));;
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Numeric value is not between 5 and 15.
     */
    public function testBetweenValidatorFailsNumberHighException()
    {
        $validator = new BetweenValidator(16, array(5,15));
        $validator->setThrow(true);
        $validator->validate();
    }

    public function testBetweenValidatorPassesString()
    {
        $validator = new BetweenValidator('this is a string', array(15,20));
        $this->assertTrue($validator->validate());
    }
    
    public function testBetweenValidatorPassesStringInclusiveLow()
    {
        $validator = new BetweenValidator('this is a strin', array(15,20));
        $this->assertTrue($validator->validate());
    }
    
    public function testBetweenValidatorPassesStringInclusiveHigh()
    {
        $validator = new BetweenValidator('this is a string bro', array(15,20));
        $this->assertTrue($validator->validate());
    }
    
    public function testBetweenValidatorFailsStringLow()
    {
        $validator = new BetweenValidator('this is a', array(15,20));
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('String length is not between 15 and 20 characters.'));;
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage String length is not between 15 and 20 characters.
     */
    public function testBetweenValidatorFailsStringLowException()
    {
        $validator = new BetweenValidator('this is a', array(15,20));
        $validator->setThrow(true);
        $validator->validate();
    }
    
    public function testBetweenValidatorFailsStringHigh()
    {
        $validator = new BetweenValidator('this is a string broseph', array(15,20));
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('String length is not between 15 and 20 characters.'));;
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage String length is not between 15 and 20 characters.
     */
    public function testBetweenValidatorFailsStringHighException()
    {
        $validator = new BetweenValidator('this is a string broseph', array(15,20));
        $validator->setThrow(true);
        $validator->validate();
    }
    
    /**
     * The constructor should throw a data exception if it isn't sent an array
     *
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage Input to between validator must be an array of two numbers.
     */
    public function testConstructorBadDataNull()
    {
        $validator = new BetweenValidator('This is a string.');
    }

    /**
     * The constructor should throw a data exception if it isn't sent an array
     *
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage Input to between validator must be an array of two numbers.
     */
    public function testConstructorBadDataNotArray()
    {
        $validator = new BetweenValidator('This is a string.', 15);
    }

    /**
     * The constructor should throw a data exception if it isn't sent an array
     *
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage Input to between validator must be an array of two numbers.
     */
    public function testConstructorBadDataArrayTooSmall()
    {
        $validator = new BetweenValidator('This is a string.', array(15));
    }

    /**
     * The constructor should throw a data exception if it isn't sent an array
     *
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage Input to between validator must be an array of two numbers.
     */
    public function testConstructorBadDataArrayTooBig()
    {
        $validator = new BetweenValidator('This is a string.', array(5,10,15));
    }

    /**
     * The constructor should throw a data exception if the values array doesn't have numbers.
     *
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage Comparison values may only be numbers.
     */
    public function testConstructorBadDataArrayNumbersOne()
    {
        $validator = new BetweenValidator('This is a string.', array('one', 1));
    }

    /**
     * The constructor should throw a data exception if the values array doesn't have numbers.
     *
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage Comparison values may only be numbers.
     */
    public function testConstructorBadDataArrayNumbersTwo()
    {
        $validator = new BetweenValidator('This is a string.', array(1, 'one'));
    }

    /**
     * The constructor should throw a data exception if the values array doesn't have numbers.
     *
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage Comparison values may only be numbers.
     */
    public function testConstructorBadDataArrayNumbersBoth()
    {
        $validator = new BetweenValidator('This is a string.', array('one', 'two'));
    }

    /**
     * The constructor should throw a data exception cell one is greater than cell two.
     *
     * @expectedException Tdphillipsjr\Validator\DataException
     * @expectedMessage The minimum value can not be larger than the maximum value.
     */
    public function testConstructorBadDataArrayNumbersFormat()
    {
        $validator = new BetweenValidator('This is a string.', array(2,1));
    }
}

?>