<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\ChoiceValidator;

/**
 * It would be almost impossible to exhaustively test this, so I'm testing cases I've actually
 * used in the code.  Which is using this as regex test more than a validator test but the regex
 * tester is actually more useful in this case.
 */
class ChoiceValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->numbers = array(1,2,3,4,5);
        $this->strings = array('one','two','three','four','five');
        $this->mixed   = array('one',2,'three',4,'five');
    }
    
    public function testNumberChoicesPass()
    {
        $validator = new ChoiceValidator(1, $this->numbers);
        $this->assertTrue($validator->validate());
    }
    
    public function testNumberChoicesFails()
    {
        $validator = new ChoiceValidator(6, $this->numbers);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testStringChoicesPass()
    {
        $validator = new ChoiceValidator('one', $this->strings);
        $this->assertTrue($validator->validate());
    }
    
    public function testStringChoicesFails()
    {
        $validator = new ChoiceValidator('six', $this->strings);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testStringChoicesFailsTwo()
    {
        $validator = new ChoiceValidator(2, $this->strings);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testMixedChoicesPass()
    {
        $validator = new ChoiceValidator('one', $this->mixed);
        $this->assertTrue($validator->validate());
    }
    
    public function testMixedChoicesFails()
    {
        $validator = new ChoiceValidator('six', $this->mixed);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testMultipleChoicePassAllMatch()
    {
        $validator = new ChoiceValidator(array(1,2,3), $this->numbers);
        $this->assertTrue($validator->validate());
    }
    
    public function testMultipleChoiceFailSomeMatch()
    {
        $validator = new ChoiceValidator(array(1,'no','match',2), $this->numbers);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testMultipleChoiceFailNoMatch()
    {
        $validator = new ChoiceValidator(array('no','match'), $this->numbers);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
}

?>