<?php
namespace Tests\Unit\Services\Validators;

use BeeDelivery\Benjamin\Models\Configs\Config;
use BeeDelivery\Benjamin\Services\Validators\BaseValidator;
use Tests\TestCase;

class BaseValidatorTest extends TestCase
{
    public function testErrorIO()
    {
        $validator = new TestValidator(new Config());
        $this->assertFalse($validator->hasErrors());
        $validator->validate();
        $this->assertTrue($validator->hasErrors());
        $this->assertArraySubset([
            'Error 1',
            'Error 2',
            'Error 3',
        ], $validator->getErrors());
    }
}

class TestValidator extends BaseValidator
{
    public function validate()
    {
        $this->addError('Error 1');
        $this->addAllErrors(['Error 2', 'Error 3']);
    }
}
