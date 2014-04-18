<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\EmailValidator;

class EmailValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->valid = array('tom@transactionreporting.com',
                             'tom.phillips@transactionreporting.com',
                             'tom@something.else.com',
                             'tom@somewhere.ez',
                             't.d.p@u231.com');

        $this->invalid = array('@something.com',
                               '+123@somewhere',
                               '823@x.somewhere',
                               'test',
                               123,
                               'tom@somewhere.c0m');
    }
    
    public function testEmailValidatorPasses()
    {
        foreach ($this->valid as $valid) {
            $validator = new EmailValidator($valid);
            $this->assertTrue($validator->validate());
        }
    }
    
    public function testEmailValidatorFails()
    {
        foreach ($this->invalid as $invalid) {
            $validator = new EmailValidator($invalid);
            $validator->setThrow(false);
            $this->assertFalse($validator->validate());
        }
    }
}
?>