<?php
namespace Tests\Unit\Services\Adapters;

use BeeDelivery\Benjamin\Models\Address;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Models\Person;
use BeeDelivery\Benjamin\Services\Adapters\CashPaymentAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use BeeDelivery\Benjamin\Models\Configs\Config;
use Tests\TestCase;

class CashPaymentAdapterTest extends TestCase
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('es_MX');
        $payment = $factory->payment()->baloto()->build();

        $adapter = new CashPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['paymentSchema', 'cashPaymentSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }

    public function testDueDateIsInsidePayment()
    {
        $payment = new Payment([
            'dueDate' => new \DateTime(),
            'person' => new Person(),
            'address' => new Address(),
        ]);

        $adapter = new CashPaymentAdapter($payment, new Config());
        $result = $adapter->transform();

        $this->assertObjectHasAttribute('due_date', $result->payment);
    }

    public function testRequestAttributeNumber()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('es_MX');
        $payment = $factory->payment()->baloto()->build();

        $adapter = new CashPaymentAdapter($payment, $config);
        $result = $adapter->transform();

        $numberOfKeys = count((array) $result);
        $this->assertEquals(5, $numberOfKeys);
        $this->assertObjectHasAttribute('integration_key', $result);
        $this->assertObjectHasAttribute('operation', $result);
        $this->assertObjectHasAttribute('mode', $result);
        $this->assertObjectHasAttribute('metadata', $result);
        $this->assertObjectHasAttribute('payment', $result);
    }
}
