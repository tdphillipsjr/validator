<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\Validatable;
use Tdphillipsjr\Validator\Validator;

class MockClass implements Validatable
{
    public $data = array('type'  => 1,
                         'value' => 'some data',
                         'more'  => 'stuff');

    public function getData()
    {
        return $this->data;
    }
    
    public function getSchema()
    {
        return array('type'  => 'required',
                     'value' => 'max:20',
                     'more'  => 'choice:stuff,here');
    }
    
    public function validate(Validator $validator)
    {
        return $validator->validateObject($this);
    }
}


class ValidatableTest extends \PHPUnit_Framework_TestCase
{
    public function testGetData()
    {
        $mock = new MockClass();
        $expected = array('type' => 1, 'value' => 'some data', 'more' => 'stuff');
        $this->assertEquals($expected, $mock->getData());
    }
    
    public function testGetSchema()
    {
        $mock = new MockClass();
        $expected = array('type'  => 'required',
                          'value' => 'max:20',
                          'more'  => 'choice:stuff,here');
        $this->assertEquals($expected, $mock->getSchema());
    }
    
    public function testValidatePass()
    {
        $validator  = new Validator();
        $mock       = new MockClass();
        $this->assertTrue($mock->validate($validator));
    }
    
    public function testValidatorFail()
    {
        $mock      = new MockClass();
        unset($mock->data['type']);
        $validator = new Validator();
        $this->setExpectedException('\Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($mock->validate($validator));
    }
}
        