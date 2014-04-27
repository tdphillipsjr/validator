<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->parser = new Parser();
    }
    
    public function testGetValidations()
    {
        $validationString = 'required||max:10||email||regex:/^(1|2|3)$/';
        $expected = array('required', 'max:10', 'email', 'regex:/^(1|2|3)$/');
        $this->assertEquals($expected, $this->parser->getValidations($validationString));
    }

    public function testParseCallNoParameters()
    {
        $testString = 'test';
        $expected = array('class' => '\Tdphillipsjr\Validator\TestValidator');
        $this->assertEquals($expected, $this->parser->parseCall($testString));
    }
    
    public function testParseCallOneParameter()
    {
        $testString = 'test:1';
        $expected = array('class'       => '\Tdphillipsjr\Validator\TestValidator',
                          'parameters'  => 1);
        $this->assertEquals($expected, $this->parser->parseCall($testString));
    }
    
    public function testParseCallMultiParameters()
    {
        $testString = 'test:1,2,3';
        $expected = array('class'       => '\Tdphillipsjr\Validator\TestValidator',
                          'parameters'  => array(1,2,3));
        $this->assertEquals($expected, $this->parser->parseCall($testString));
    }

    public function testParseParameters()
    {
        $testString = 'requiredIf:type,1,2,3';
        $expected = array('index'   => 'type',
                          'values'  => array(1,2,3));
        $this->assertEquals($expected, $this->parser->parseParameters($testString));
    }

    public function testParseParametersSingle()
    {
        $testString = 'requiredIf:type,1';
        $expected = array('index'   => 'type',
                          'values'  => array(1));
        $this->assertEquals($expected, $this->parser->parseParameters($testString));
    }
    
    public function testParseParametersNoValues()
    {
        $testString = 'requiredIf:data';
        $expected = array('index'  => 'data',
                          'values' => array());
        $this->parser->parseParameters($testString);
    }
    
    public function testParseParametersNoValuesComma()
    {
        $testString = 'requiredIf:data,';
        $expected = array('index'  => 'data',
                          'values' => array());
        $this->parser->parseParameters($testString);
    }
    
    public function testExtractDataFound()
    {
        $validations = array('max:15',
                             'min:2',
                             'required',
                             'requiredIf:type,1');
        $expected = 'requiredIf:type,1';
        $this->assertEquals($expected, $this->parser->extractValidation('requiredIf', $validations));
    }
        
    public function testExtractDataNotFound()
    {
        $validations = array('max:15',
                             'min:2',
                             'required',
                             'requiredIf:type,1');
        $this->assertFalse($this->parser->extractValidation('thomas', $validations));
    }
    
    public function testHasValidationTypePass()
    {
        $validationType = 'requiredIf';
        $validations = array('requiredIf:type,1,2,3',
                             'max:240',
                             'min:123',
                             'number');
        $this->assertTrue($this->parser->hasValidationType($validationType, $validations));
    }
    
    public function testHasValidationTypeFail()
    {
        $validationType = 'notRequiredIf';
        $validations = array('requiredIf:type,1,2,3',
                             'max:240',
                             'min:123',
                             'number');
        $this->assertFalse($this->parser->hasValidationType($validationType, $validations));
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ParseException
     * @expectedExceptionMessage hasValidationType usage; second argument must be an array.
     */
    public function testHasValidationTypeException()
    {
        $this->parser->hasValidationType('requiredIf', 'requiredIf:type,1,2,3');
    }
}