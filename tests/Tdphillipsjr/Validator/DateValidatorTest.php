<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\DateValidator;

class DateValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testDateValidatorPasses()
    {
        $testDate = '06/15/1955';
        $validator = new DateValidator($testDate, 'm/d/Y');
        $this->assertTrue($validator->validate());
    }
    
    public function testDateValidatorTwoDigitYear()
    {
        $testDate = '06/15/55';
        $validator = new DateValidator($testDate, 'm/d/y');
        $this->assertTrue($validator->validate());
    }
    
    public function testDateValidatorNonLeadingValues()
    {
        $testDate = '6/1/55';
        $validator = new DateValidator($testDate, 'n/j/y');
        $this->assertTrue($validator->validate());
    }
    
    public function testDateValidatorFailsMismatchMonth()
    {
        $testDate = '06/15/1955';

        $validator = new DateValidator($testDate, 'n/d/Y');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('Date is not in the expected format.'));
    }
    
    public function testDateValidatorFailsMismatchDay()
    {
        $testDate = '06/01/1955';

        $validator = new DateValidator($testDate, 'm/j/Y');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('Date is not in the expected format.'));
    }
    
    public function testDateValidatorFailsMismatchDayTwo()
    {
        $testDate = '06/1/1955';

        $validator = new DateValidator($testDate, 'm/d/Y');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('Date is not in the expected format.'));
    }
    
    public function testDateValidatorFailsMismatchYear()
    {
        $testDate = '06/15/55';

        $validator = new DateValidator($testDate, 'm/d/Y');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('Date is not in the expected format.'));
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Date is not in the expected format.
     */
    public function testDateValidatorFailsMismatchFormatThrow()
    {
        $testDate = '06/15/55';

        $validator = new DateValidator($testDate, 'm/d/Y');
        $validator->setThrow(true);
        $validator->validate();
    }
    
    public function testDateValidatorFailsInvalid()
    {
        $testDate = '15/31/1955';
        
        $validator = new DateValidator($testDate, 'm/d/Y');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
        $this->assertEquals($validator->getErrors(), array('Invalid date entered.'));
    }
    
    /**
     * @expectedException Tdphillipsjr\Validator\ValidationException
     * @expectedMessage Invalid date entered.
     */
    public function testDateValidatorFailsInvalidThrow()
    {
        $testDate = '15/31/1955';
        
        $validator = new DateValidator($testDate, 'm/d/Y');
        $validator->setThrow(true);
        $validator->validate();
    }
    
    public function testDateValidNoFormatMask()
    {
        $testDate = "12/31/1945";
        $validator = new DateValidator($testDate);
        $this->assertTrue($validator->validate());
    }
    
    public function testDateValidNoFormatMaskWithTime()
    {
        $testDate = "12/31/1945 12:15:12";
        $validator = new DateValidator($testDate);
        $this->assertTrue($validator->validate());
    }
    
    public function testDateInvalidNoFormatBadDate()
    {
        $testDate = "12/85/1955";
        $validator = new DateValidator($testDate);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testDateInvalidNoFormatBadTime()
    {
        $testDate = "12/15/1955 30:24:12";
        $validator = new DateValidator($testDate);
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
}
?>