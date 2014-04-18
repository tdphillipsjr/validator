<?php

namespace Tdphillipsjr\Validator;

use Tdphillipsjr\Validator\RegexValidator;

/**
 * It would be almost impossible to exhaustively test this, so I'm testing cases I've actually
 * used in the code.  Which is using this as regex test more than a validator test but the regex
 * tester is actually more useful in this case.
 */
class RegexValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testFourDigitsPass()
    {
        $validator = new RegexValidator(1234, '/^\d{4}$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testFourDigitsLeadingZeroPass()
    {
        $validator = new RegexValidator('0123', '/^\d{4}$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testFourDigitInvalidCharacterFail()
    {
        $validator = new RegexValidator('test', '/^\d{4}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testFourDigitTooShortFail()
    {
        $validator = new RegexValidator(12, '/^\d{4}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testFourDigitTooLongFail()
    {
        $validator = new RegexValidator(12345, '/^\d{4}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testFiveDigitsPass()
    {
        $validator = new RegexValidator(12345, '/^\d{5}$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testFiveDigitsLeadingZeroPass()
    {
        $validator = new RegexValidator('01234', '/^\d{5}$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testFiveDigitInvalidCharacterFail()
    {
        $validator = new RegexValidator('test', '/^\d{5}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testFiveDigitTooShortFail()
    {
        $validator = new RegexValidator(12, '/^\d{5}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testFiveDigitTooLongFail()
    {
        $validator = new RegexValidator(123456, '/^\d{5}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testNineDigitsPass()
    {
        $validator = new RegexValidator(123456789, '/^\d{9}$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testNineDigitsLeadingZeroPass()
    {
        $validator = new RegexValidator('012345678', '/^\d{9}$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testNineDigitInvalidCharacterFail()
    {
        $validator = new RegexValidator('test', '/^\d{9}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testNineDigitTooShortFail()
    {
        $validator = new RegexValidator(12, '/^\d{9}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testNineDigitTooLongFail()
    {
        $validator = new RegexValidator(1234567890, '/^\d{9}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testAlphanumericOnlyPass()
    {
        $validator = new RegexValidator('This and That 123', '/^[a-zA-Z0-9 ]+$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testAlphanumericOnlyFail()
    {
        $validator = new RegexValidator('This & That 123', '/^[a-zA-Z0-9 ]+$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    /**
     * This is an ugly regex that verifies only approved, three-letter abbreviations of months are
     * passed in a string without spaces.
     */
    public function testMonthStringPass()
    {
        $validator = new RegexValidator('JAN', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testMonthStringPassTwo()
    {
        $validator = new RegexValidator('JAN,FEB', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $this->assertTrue($validator->validate());
    }
        
    public function testMonthStringPassThree()
    {
        $validator = new RegexValidator('JAN,JUN,FEB', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $this->assertTrue($validator->validate());
    }

    public function testMonthStringPassFour()
    {
        $validator = new RegexValidator('JAN,JUN,FEB,JAN', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $this->assertTrue($validator->validate());
    }

    public function testMonthStringFails()
    {
        $validator = new RegexValidator('JAN,TOM', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testMonthStringFailsTwo()
    {
        $validator = new RegexValidator('jan', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testMonthStringFailsThree()
    {
        $validator = new RegexValidator('JAN, FEB', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testMonthStringFailsFour()
    {
        $validator = new RegexValidator('JAN FEB', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testMonthStringFailsFive()
    {
        $validator = new RegexValidator('JANFEB', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testMonthStringFailsSix()
    {
        $validator = new RegexValidator('JAN,FEBMAR', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }

    public function testMonthStringFailsSeven()
    {
        $validator = new RegexValidator('JAN,FEB MAR', 
                                        '/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)(,(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC))*$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testDigitCountMatches()
    {
        $number = '';
        
        for ($i=1; $i<20; $i++) {
            $validator = new RegexValidator($number, '/^\d{8,16}$/');
            
            if (empty($number) || strlen($number) < 8 || strlen($number) > 16) {
                $validator->setThrow(false);
                $this->assertFalse($validator->validate());
            } else {
                $this->assertTrue($validator->validate());
            }
            
            $number = $number . '1';
        }
    }
    
    public function testDigitCountMatchesPassedAsArray()
    {
        $number = '';
        $pattern = array('/^\d{8', 
                         '16}$/');
        
        for ($i=1; $i<20; $i++) {
            $validator = new RegexValidator($number, $pattern);
            
            if (empty($number) || strlen($number) < 8 || strlen($number) > 16) {
                $validator->setThrow(false);
                $this->assertFalse($validator->validate());
            } else {
                $this->assertTrue($validator->validate());
            }
            
            $number = $number . '1';
        }
    }
    
    public function testDigitCountFailsLetters()
    {
        $validator = new RegexValidator('test', '/^\d{8,16}$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    public function testAlphanumAndDashesPassNumbers()
    {
        $validator = new RegexValidator('123', '/^[a-zA-Z0-9-]+$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testAlphanumAndDashesPassLetters()
    {
        $validator = new RegexValidator('abc', '/^[a-zA-Z0-9-]+$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testAlphanumAndDashesPassDashes()
    {
        $validator = new RegexValidator('---', '/^[a-zA-Z0-9-]+$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testAlphanumAndDashesPassMix()
    {
        $validator = new RegexValidator('abc-123', '/^[a-zA-Z0-9-]+$/');
        $this->assertTrue($validator->validate());
    }
    
    public function testAlphanumAndDashesFails()
    {
        $validator = new RegexValidator('abc&123-e3e', '/^[a-zA-Z0-9-]+$/');
        $validator->setThrow(false);
        $this->assertFalse($validator->validate());
    }
    
    
    
    
}

?>