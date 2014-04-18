<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\NumberValidator;

class NumberValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testNumberValidatorPasses()
    {
        $validator = new NumberValidator(1);
        $this->assertTrue($validator->validate());
    }
    
    public function testNumberValidatorPassesFloat()
    {
        $validator = new NumberValidator(12.45);
        $this->assertTrue($validator->validate());
    }
    
    public function testNumberValidatorFails()
    {
        $validator = new NumberValidator('one');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
}

?>