<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\UrlValidator;

class UrlValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->valid = array('http://www.example.com',
                             'www.somewhere.com',
                             'https://www.example.com',
                             'http://example.com',
                             'http://tom.example.com',
                             'something.com',
                             'http://tom.example.com/whatever');

        $this->invalid = array('http://&what.something.com',
                               'http://what.+-oxie.com',
                               123,
                               'text',
                               'https://www.something.$om/',
                               'http://www.something.com/$$$');
    }
    
    public function testUrlValidatorPasses()
    {
        foreach ($this->valid as $valid) {
            $validator = new UrlValidator($valid);
            $this->assertTrue($validator->validate());
        }
    }
    
    public function testUrlValidatorFails()
    {
        foreach ($this->invalid as $invalid) {
            $validator = new UrlValidator($invalid);
            $validator->setThrow(false);
            $this->assertFalse($validator->validate());
        }
    }
}
?>