<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\Validator;

class MockObject
{
    private $validator;
    
    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }
    
    public function loadValidator($data, $schema)
    {
        $this->validator->loadData($data);
        $this->validator->loadSchema($schema);
    }
    
    public function validate()
    {
        return $this->validator->validate();
    }
}

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->data = array('type'      => 1,
                            'value'     => 15,
                            'name'      => 'tom',
                            'other_name'=> 'phillips',
                            'water'     => 'bottle',
                            'reqd'      => 'hi',
                            'nreqd'     => 'blah',
                            'beer'      => 'stein',
                            'wine'      => 'carafe',
                            'between'   => 33,
                            'month'     => 'jan',
                            'date'      => '03/04/1999',
                            'email'     => 'tom@something.com',
                            'count'     => 15,
                            'floater'   => 15.4,
                            'phone'     => '5185551212',
                            'zip'       => '12345',
                            'ssn'       => '123-45-6789',
                            'state'     => 'NY',
                            'ein'       => '12-3456789',
                            'pct1'      => '50',
                            'pct2'      => '50',
                            'require'   => 'yes',
                        );

        $this->schema = array('type'        => 'required',
                              'value'       => 'max:15||min:2',
                              'name'        => 'required',
                              'other_name'  => 'requiredIf:type,1',
                              'water'       => 'requiredIf:type,1,2',
                              'reqd'        => 'requiredIf:value,10',
                              'nreqd'       => 'notRequiredIf:value,11',
                              'wine'        => 'notRequiredIf:type,3',
                              'beer'        => 'notRequiredIf:type,3,4',
                              'between'     => 'between:30,35',
                              'month'       => 'choice:jan,feb,mar',
                              'date'        => 'date:m/d/Y',
                              'email'       => 'email',
                              'count'       => 'number',
                              'floater'     => 'number',
                              'phone'       => 'phone',
                              'zip'         => 'regex:/^\d{5}$/',
                              'ssn'         => 'ssn',
                              'ein'         => 'tin',
                              'pct1'        => 'total:100,pct2',
                              'pct2'        => 'total:100,pct1',
                              'require'     => 'required',
                         );
    }
    
    private function _createValidator()
    {
        $validator = new Validator();
        $validator->loadData($this->data);
        $validator->loadSchema($this->schema);
        return $validator;
    }
    
    public function testValidateEachTypePass()
    {
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    public function testValidateRequiredFieldUnset()
    {
        unset($this->data['require']);
        $validator = $this->_createValidator();
        
        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('require is required'));
    }
    
    public function testValidateRequiredFieldEmpty()
    {
        $this->data['type'] = '';
        $validator = $this->_createValidator();
        
        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('type is required'));
    }
    
    /**
     * If type is 2, water is required to have a value.
     */
    public function testValidateRequiredIfUnsetSingle()
    {
        $this->data['type'] = 2;
        unset($this->data['water']);
        $validator = $this->_createValidator();

        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array("water is required because type has one of the selected values."));
    }
    
    public function testValidateRequiredIfEmptySingle()
    {
        $this->data['type'] = 2;
        $this->data['water'] = '';
        $validator = $this->_createValidator();

        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array("water is required because type has one of the selected values."));
    }
    
    /**
     * if type is 1, both water and other_name are required to have values.
     */
    public function testValidateRequiredIfUnsetMultiple()
    {
        $this->data['type'] = 1;
        unset($this->data['water']);
        unset($this->data['other_name']);
        $validator = $this->_createValidator();

        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("other_name is required because type has one of the selected values.",
                          "water is required because type has one of the selected values.");
        $this->assertEquals($validator->getErrors(), $expected);
    }

    public function testValidateRequiredIfEmptyMultiple()
    {
        $this->data['type']         = 1;
        $this->data['water']        = '';
        $this->data['other_name']   = '';
        $validator = $this->_createValidator();

        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("other_name is required because type has one of the selected values.",
                          "water is required because type has one of the selected values.");
        $this->assertEquals($validator->getErrors(), $expected);
    }
    
    /**
     * If type is 3, neither water or or other_name is required to have a value
     */
    public function testValidateRequiredIfValueNotRequiredSingle()
    {
        $this->data['type'] = 3;
        unset($this->data['water']);
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }

    public function testValidateRequiredIfValueNotRequiredSingleEmpty()
    {
        $this->data['type'] = 3;
        $this->data['water'] = '';
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }

    public function testValidateRequiredIfValueNotRequiredMultiple()
    {
        $this->data['type'] = 3;
        unset($this->data['other_name']);
        unset($this->data['water']);
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }

    public function testValidateRequiredIfValueNotRequiredMultipleEmpty()
    {
        $this->data['type'] = 3;
        $this->data['other_name'] = '';
        $this->data['water'] = '';
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    public function testValidateRequiredIfNoData()
    {
        $this->schema['other_name'] = 'requiredIf:water';
        $validator = $this->_createValidator();
        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("other_name: requiredIf with no values is not supported.");
        $this->assertEquals($validator->getErrors(), $expected);
    }

    /**
     * The field with the required if value does not exist and the required if check is not triggered.
     * should validate.
     */
    public function testValidateRequiredIfFieldDoesntExistPass()
    {
        $this->data['value'] = 5;
        unset($this->data['reqd']);
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    /**
     * The field with the required if value does not exist and the required if check is triggered.
     * should not validate.
     */
    public function testValidateRequiredIfFieldDoesntExistFail()
    {
        $this->data['value'] = 10;
        unset($this->data['reqd']);
        $validator = $this->_createValidator();
        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("reqd is required because value has one of the selected values.");
        $this->assertEquals($validator->getErrors(), $expected);
    }
    
    /**
     * The field with the required if value exists but the keyed field does not exist.  
     * Should validate
     */
    public function testValidateRequiredIfFieldKeyedFieldDoesntExist()
    {
        $this->data['reqd'] = 'blah';
        unset($this->data['value']);
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    /**
     * Neither field exists.
     * should validate
     */
    public function testValidateRequiredIfNeitherFieldExists()
    {
        unset($this->data['reqd']);
        unset($this->data['value']);
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    /**
     * If type is 4, beer is not required to have a value.
     */
    public function testValidateNotRequiredIfUnsetSingle()
    {
        $this->data['type'] = 4;
        unset($this->data['beer']);
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    public function testValidateNotRequiredIfEmptySingle()
    {
        $this->data['type'] = 4;
        $this->data['beer'] = '';
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    /**
     * If type is 3, neither wine nor beer is required to have a value
     */
    public function testValidateNotRequiredIfUnsetMultiple()
    {
        $this->data['type'] = 3;
        unset($this->data['beer']);
        unset($this->data['wine']);
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    public function testValidateNotRequiredIfEmptyMultiple()
    {
        $this->data['type'] = 3;
        $this->data['beer'] = '';
        $this->data['wine'] = '';
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    /**
     * If type is 1, both wine and beer must have a value.
     */
    public function testValidateNotRequiredIfValueRequiredSingleUnset()
    {
        $this->data['type'] = 1;
        unset($this->data['beer']);
        $validator = $this->_createValidator();

        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("beer is required because type does not contain an exempt value.");
        $this->assertEquals($validator->getErrors(), $expected);
    }

    public function testValidateNotRequiredIfEmptyMultipleUnset()
    {
        $this->data['type'] = 1;
        unset($this->data['beer']);
        unset($this->data['wine']);
        $validator = $this->_createValidator();

        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("wine is required because type does not contain an exempt value.",
                          "beer is required because type does not contain an exempt value.");
        $this->assertEquals($validator->getErrors(), $expected);
    }
        
    public function testValidateNotRequiredIfValueRequiredSingleEmtpy()
    {
        $this->data['type'] = 1;
        $this->data['beer'] = '';
        $validator = $this->_createValidator();

        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("beer is required because type does not contain an exempt value.");
        $this->assertEquals($validator->getErrors(), $expected);
    }

    public function testValidateNotRequiredIfEmptyMultipleEmpty()
    {
        $this->data['type'] = 1;
        $this->data['beer'] = '';
        $this->data['wine'] = '';
        $validator = $this->_createValidator();

        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $validator->validate();
        $expected = array("wine is required because type does not contain an exempt value.",
                          "beer is required because type does not contain an exempt value.");
        $this->assertEquals($validator->getErrors(), $expected);
    }

    public function testValidateNotRequiredIfNoData()
    {
        $this->schema['wine'] = 'notRequiredIf:water';
        $validator = $this->_createValidator();
        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("other_name: notRequiredIf with no values is not supported.");
        $this->assertEquals($validator->getErrors(), $expected);
    }

    /**
     * The field with the not required if value does not exist and the not required check is not triggered.
     * should not validate because there is no exempt value so nrequd is required
     */
    public function testValidateNotRequiredIfFieldDoesntExistPass()
    {
        $this->data['value'] = 5;
        unset($this->data['nreqd']);
        $validator = $this->_createValidator();
        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("nreqd is required because value does not contain an exempt value.");
        $this->assertEquals($validator->getErrors(), $expected);
    }
    
    /**
     * The field with the not required if value does not exist and the not required if check is triggered.
     * should validate because nreqd is exempt.
     */
    public function testValidateNotRequiredIfFieldDoesntExistFail()
    {
        $this->data['value'] = 11;
        unset($this->data['nreqd']);
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    /**
     * The field with the not required if value does not exist and the keyed value does not exist.
     * Should not validate because the field doesn't exist so the other value is required
     */
    public function testValidateNotRequiredIfBothFieldFieldDoesntExist()
    {
        unset($this->data['nreqd']);
        unset($this->data['value']);
        $validator = $this->_createValidator();
        $this->setExpectedException('Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
        $expected = array("reqd is required because value has one of the selected values.");
        $this->assertEquals($validator->getErrors(), "nreqd is required because value does not contain an exempt value.");
    }
    
    /**
     * If the field with the keyed value doesn't exist and the value exists, should pass.
     */
    public function testValidateNotRequiredIfKeyedFieldDoesntExist()
    {
        unset($this->data['value']);
        $this->data['nreqd'] = 'hi';
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }

    public function testFieldExists()
    {
        $validator = $this->_createValidator();
        $this->assertTrue($validator->fieldExists('type'));
    }
    
    public function testFieldExistsNull()
    {
        $this->data['type'] = null;
        $validator = $this->_createValidator();
        $this->assertFalse($validator->fieldExists('type'));
    }
    
    public function testFieldExistsEmptyString()
    {
        $this->data['type'] = '';
        $validator = $this->_createValidator();
        $this->assertFalse($validator->fieldExists('type'));
    }
    
    public function testFieldExistsFalse()
    {
        $this->data['type'] = false;
        $validator = $this->_createValidator();
        $this->assertFalse($validator->fieldExists('type'));
    }
    
    public function testFieldExistsNumericZero()
    {
        $this->data['type'] = 0;
        $validator = $this->_createValidator();
        $this->assertTrue($validator->fieldExists('type'));
    }
    
    public function testFieldExistsCharacterZero()
    {
        $this->data['type'] = '0';
        $validator = $this->_createValidator();
        $this->assertTrue($validator->fieldExists('type'));
    }
    
    public function testFieldExistsDoesntExist()
    {
        unset($this->data['type']);
        $validator = $this->_createValidator();
        $this->assertFalse($validator->fieldExists('type'));
    }
    
    public function testIsRequiredIsRequiredSingle()
    {
        $validator = $this->_createValidator();
        $this->assertTrue($validator->isRequired(array('required')));
    }
    
    public function testIsRequiredIsRequiredMultiple()
    {
        $validator = $this->_createValidator();
        $this->assertTrue($validator->isRequired(array('required', 'min:2')));
    }
    
    public function testIsRequiredRequiredIfSingleRequired()
    {
        $this->data['type'] = 1;
        $validator = $this->_createValidator();
        
        $this->assertTrue($validator->isRequired(array('requiredIf:type,1')));
    }
    
    public function testIsRequiredRequiredIfMultipleRequired()
    {
        $this->data['type'] = 1;
        $validator = $this->_createValidator();
        
        $this->assertTrue($validator->isRequired(array('requiredIf:type,1,2', 'max:15')));
    }
    
    public function testIsRequiredRequiredIfSingleNotRequired()
    {
        $this->data['type'] = 2;
        $validator = $this->_createValidator();
        
        $this->assertFalse($validator->isRequired(array('requiredIf:type,1')));
    }
        
    public function testIsRequiredRequiredIfMultipleNotRequired()
    {
        $this->data['type'] = 3;
        $validator = $this->_createValidator();
        
        $this->assertFalse($validator->isRequired(array('requiredIf:type,1,2', 'max:15')));
    }
        
    public function testIsRequiredNotRequiredIfSingleRequired()
    {
        $this->data['type'] = 1;
        $validator = $this->_createValidator();

        $this->assertTrue($validator->isRequired(array('notRequiredIf:type,3')));
    }
    
    public function testIsRequiredNotRequiredIfMultipleRequired()
    {
        $this->data['type'] = 1;
        $validator = $this->_createValidator();

        $this->assertTrue($validator->isRequired(array('notRequiredIf:type,3,4', 'max:15')));
    }
    
    public function testIsRequiredNotRequiredIfSingleNotRequired()
    {
        $this->data['type'] = 3;
        $validator = $this->_createValidator();

        $this->assertFalse($validator->isRequired(array('notRequiredIf:type,3')));
    }
        
    public function testIsRequiredNotRequiredIfMultipleNotRequired()
    {
        $this->data['type'] = 3;
        $validator = $this->_createValidator();

        $this->assertFalse($validator->isRequired(array('notRequiredIf:type,3,4', 'max:15')));
    }
    
    public function testIsRequiredNotRequiredSingle()
    {
        $validator = $this->_createValidator();
        $this->assertFalse($validator->isRequired(array('max:15')));
    }
    
    public function testIsRequiredNotRequiredMultiple()
    {
        $validator = $this->_createValidator();
        $this->assertFalse($validator->isRequired(array('max:15', 'min:2')));
    }
    
    public function testRequiredBecauseRequired()
    {
        $validator = $this->_createValidator();
        $this->assertEquals('.', $validator->requiredBecause(array('required', 'max:14')));
    }
    
    public function testRequiredBecauseRequiredIf()
    {
        $validator = $this->_createValidator();
        $expected  = ' because type has one of the selected values.';
        $this->assertEquals($expected, $validator->requiredBecause(array('requiredIf:type:1,2', 'max:14')));
    }
    
    public function testRequiredBecauseNotRequiredIf()
    {
        $validator = $this->_createValidator();
        $expected  = ' because type does not contain an exempt value.';
        $this->assertEquals($expected, $validator->requiredBecause(array('notRequiredIf:type:1,2', 'max:14')));
    }
    
    public function testRequiredBecauseNotRequired()
    {
        $validator = $this->_createValidator();
        $this->assertEquals('', $validator->requiredBecause(array('min:2', 'max:14')));
    }

    public function testDependencyInjectionPass()
    {
        $mock       = new MockObject();
        $validator  = new Validator();
        
        $mock->setValidator($validator);
        $data   = array('id' => 1234);
        $schema = array('id' => 'required');
        $mock->loadValidator($data, $schema);
        
        $this->assertTrue($mock->validate());
    }

    public function testDependencyInjectionFail()
    {
        $mock = new MockObject();
        $validator = new Validator();
        
        $mock->setValidator($validator);
        $data   = array('id' => null);
        $schema = array('id' => 'required');
        $mock->loadValidator($data, $schema);
        
        $this->setExpectedException('\Tdphillipsjr\Validator\ValidatorException');
        $mock->validate();
    }
    
    public function testMinLanguageSinglePass()
    {
        $this->data = array('type' => 2);
        $this->schema = array('type' => 'min:1');
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
    
    public function testMinLanguageSingleFail()
    {
        $this->data = array('type' => 0);
        $this->schema = array('type' => 'min:1');
        $validator = $this->_createValidator();
        $this->setExpectedException('\Tdphillipsjr\Validator\ValidatorException');
        $this->assertFalse($validator->validate());
    }
    
    public function testMinLanguageCastStringPass()
    {
        $this->data = array('type' => 0);
        $this->schema = array('type' => 'min:string,1');
        $validator = $this->_createValidator();
        $this->assertTrue($validator->validate());
    }
}
?>