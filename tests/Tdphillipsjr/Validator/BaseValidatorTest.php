<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\ValidationException;
use Tdphillipsjr\Validator\BaseValidator;

class MockValidator extends BaseValidator
{
    public function __construct($testData, $testAgainst)
    {
        parent::__construct($testData, $testAgainst);
    }
    
    public function validate()
    {
        if ($this->_data != $this->_validateAgainst) $this->addError('No match!');
        
        return !sizeof($this->_errors);
    }
}

class MockValidatorWithData extends BaseValidator
{
    public $newData = false;
    
    public function __construct($testData, $testAgainst)
    {
        parent::__construct($testData, $testAgainst);
    }
    
    public function validate()
    {
        if ($this->_data != $this->_validateAgainst) $this->addError('No match!');
        
        return !sizeof($this->_errors);
    }
    
    protected function _loadAdditionalData($data)
    {
        $this->newData = true;
    }
}


class BaseValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testBaseValidatorPass()
    {
        $mock = new MockValidator(true, true);
        
        $this->assertTrue($mock->validate());
    }
    
    /**
     * Test that the mock validation fails with and without exceptions.  This is also to test
     * setThrow and addError.
     *
     * @covers BaseValidator::setThrow
     * @covers BaseValidator::addError
     * @covers BaseValidator::validate
     */
    public function testMockValidatorFails()
    {
        $mock = new MockValidator(true, false);
        $mock->setThrow(false);
        $this->assertEquals($mock->getErrors(), array());
        $this->assertFalse($mock->validate());
        $this->assertEquals($mock->getErrors(), array('No match!'));
        
        $mock->setThrow(true);
        try {
            $mock->validate();
        } catch (ValidationException $e) {}
            
        $this->assertEquals($e->getMessage(), 'No match!');
        $this->assertInstanceOf('Tdphillipsjr\Validator\ValidationException', $e);
    }
    
    /**
     * Test that loadAdditionalData doesn't freak out if the method isn't defined.
     */
    public function testLoadAdditionalDataNoData()
    {
        $mock = new MockValidator(true, true);
        $mock->loadAdditionalData();
        $this->assertTrue($mock->validate());
    }
    
    /**
     * Test if loadAdditionalData is defined that it works.
     */
    public function testLoadAdditionalDataWithData()
    {
        $mock = new MockValidatorWithData(true, true);
        $this->assertFalse($mock->newData);
        $mock->loadAdditionalData();
        $this->assertTrue($mock->newData);
        $this->assertTrue($mock->validate());
    }
}

?>