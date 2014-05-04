#Validator

Given an array of data and an array of validation, step through each validation and see if their are any errors.  The validation
language is defined as follows:

 - Multiple validations are delimited by double-pipe ( || ) so as to not conflict with the single pipe character in regular expressions.
 - Colons delimit a validation with additional data.  Example -  "max:250" means "use the MaxValidator with input value 250".
 - Commas delimit multiple additional data to a validator.  Example - "between:1,10" means "use the Between Validator with 1 and 10."
 - The array may be broken up in different ways.  Example - "requiredIf:type,1,2" means "Required If validator, use the type index, and 1 and 2 as values."  
    It mostly depends on what your validator expects.
    
The Validator class is designed to manage multiple sub validators class, which all inherit from BaseValidator.

##Sample Usage
    $validator = new Tdphillipsjr\Validator\Validator();
    
    // Load the data in the form of an array
    $validator->loadData(array('id'     => 1,
                               'name'   => 'Tom P',
                               'slug'   => 'tom-p',
                               'age'    => 35,
                               'drink'  => 'water'));
    
    // Load the schema in the form of an array.  The indexes must match the data you want to compare.
    $validator->loadSchema(array('id'   => 'required||number',      // Field is required, must be a number
                                 'name' => 'required||max:250',     // Field is required, must be less than 250 chars
                                 'slug' => 'required||regex:/(\w)/' // Field is required, must match this regex.
                                 'age'  => 'number'                 // Field is not required, but if it's included, it must be a number.
                                 'drink'=> 'choice:water,beer'));   // Field is not required, but if it's included, it must be either "water" or "beer"
    // Run the validation.
    $validator->validate();

The above function will step through each defined validation and attempt to match it against a validator class in the directory.  Each 
word (max, regex, etc) will attempt to find a validator class in the namespace with a capitalized first letter and the word "Validator" after
it.  Thus, "max" will search for the MaxValidator.  What this means is adding a new word to the language simple requires you add a new
Validator class and the word will automatically be recognized.  For instance, if you added a "FooValidator" to the namespace, "foo" would 
become a valid input to the validation langauge.  

Important note!!  The Validator class ALWAYS attempts to submit data as an array.  So 'max:250' in a Validator _schema array will attempt to construct
MaxValidator with "new MaxValidator($yourComparison, array(250))".  So, when creating a new Validator, handle that however it makes sense for your class.

The only exceptions are "required", "requiredIf", and "notRequiredIf".  These are managed by the Validator itself and do not have a 
BaseValidator class.  These can not be called independently, but instead must go through the Validator class.  Otherwise, there is no reason you
can't simply construct the MaxValidator with some data and something to compare it to and use it.

Each SubValidator may or may not throw an exception dependent on your choice.  By default, if SubValidator->validate() fails it will
throw a Validator\ValidationException.  However, by calling SubValidator->setThrow(false), you can turn this behavior off and instead validate() will return
false if validation fails with the error stored in an error array accessible via SubValidator->getErrors().  You can also override this in the 
constructor of your SubValidator.  Just set $this->_throw to false after you call parent::__construct.

### Usage with interface
Package includes an interface Validatable.  Validatable requires functions "getSchema", "getData", and "validate".  When used, 
the function $validator->validateObject(Validatable) may be used.
    class Object implements Validatable
    {
        public function getSchema()
        {
            return array('type' => 'required');
        }
        public function getData()
        {
            return array('type' => 1);
        }
        public function validate(\Tdphillipsjr\Validator\Validator $validator)
        {
            return $validator->validateObject($this);
        }
    }
    
    $object = new Object();
    $validator = new Validator();
    $object->validate();

##Validations without SubValidators

###required
 - usage: "required"
 - behavior: If the value in the data array is unset or empty, this fails.  ANY empty value will cause this validation to fail, which means you
                you can't use boolean false, 0, null, or empty string even if these are valid inputs.
                
###requiredIf
 - usage: "requiredIf:field,1" or "requiredIf:field,1,2"
 - behavior: The first argument is expected to be an index of the data array and the rest are valid values.  If the given index has a value that
                matches one of the given values, the requiredIf field is treated as required under the same rules as required.
                
###notRequiredIf:
 - usage: "notRequiredIf:field,1" or "notRequiredIf:field,1,2,3"
 - behavior: the index that contains this validation is treated as required unless the defined field in the data array has one of the given values.  Then
                the index becomes not required.

##Current SubValidators

###BetweenValidator
 - usage: "between:1,10"
 - behavior: Validates the value is between the first and second number.  This is inclusive.  1 and 10 will validate true in the given example.
 
###ChoiceValidator
 - usage: "choice:X,Y,Z"
 - behavior: Data must be equal to one of the given choices.  Any number of choices may be defined.

###DateValidator
 - usage: "date:m/d/Y"
 - behavior: The format of the date much match the datemask given.
 - Notes: This could use some improvement.  Really only full dates work.
 
###EmailValidator
 - usage: "email"
 - behavior: Check the format of the field is a valid(ish) e-mail address.  This is not RFC-compliant but matches most common formats.
 
###EqualToValidator
 - usage: "equalTo:15"
 - behavior: Check that the data given equals the value given.  This will also compare arrays if BOTH arguments to the validator are arrays.
 - Notes:
    - This does not run ===, but just ==.
    - If both fields are array, it will compare the arrays.

###MaxValidator
 - usage: "max:250", "max:string,250"
 - behavior:
    - If string, assert the string is less than or equal to 250 characters.
    - If numeric, assert the number is less than or equal to 250.
    - Numeric values may be compared as strings by using a cast value as the first argument.  This can be used
        to compare numbers as string data, such as zip codes.
    
###MinValidator
 - usage: "min:250", "min:string,250"
 - behavior:
    - If string, assert the string is at least 250 characters.
    - If numeric, assert the number is greater than or equal to 250.
    - Numeric values may be compared as strings by using a cast value as the first argument.  This can be used
        to compare numbers as string data, such as zip codes.

###NumberValidator
 - usage: "number"
 - behavior: validate the data is a number (integer or float validates true).
 
###PhoneValidator
 - usage: "phone"
 - behavior: Validate the data is a phone number.  This only validates a 10 digit number that doesn't start with 1 or 0.  Format
                characters should be stripped.  Leading 1 should be removed.
                
###RegexValidator
 - usage: "regex:/(\w)/"
 - behavior: Validate that running preg_match with given regex as the pattern will return true.
 
###SsnValidator
 - usage: "ssn"
 - behavior: Validate this is a valid SSN number.  This should validate as digits only or in the common 123-45-6789 format.
 - Notes: this runs through a few of the government's social security number rules.
    1. Cannot be 219-09-9999
    2. Cannot be 078-05-1120
    3. Cannot start with 900 through 999
    4. Cannot start with 666
    5. Cannot have all zeroes in any group.
    
###TinValidator
 - usage: "tin"
 - behavior: Validate this is a valid Tax ID number.  This should validate as digits only or in the common 12-3456789 format.
 
###TotalValidator
 - usage: "total:100,foo,bar"
 - behavior: This field, numerically totalled with data fields "foo" and "bar" should total 100.
 
###UrlValidator
 - usage: "url"
 - behavior: This a validly formatted URL.  Not RFC inclusive but should check most common types.

##TODO
 - Other validators?
 - Database object injection
 
