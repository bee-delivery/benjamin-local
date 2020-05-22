<?php
namespace Tests\Unit\Services\Adapters;

use BeeDelivery\Benjamin\Services\Adapters\BrazilPaymentAdapter;
use Tests\Helpers\Builders\BuilderFactory;
use JsonSchema;
use BeeDelivery\Benjamin\Models\Configs\Config;
use Tests\TestCase;

class BrazilPaymentAdapterTest extends TestCase
{
    public function testJsonSchema()
    {
        $config = new Config([
            'sandboxIntegrationKey' => 'testIntegrationKey'
        ]);
        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->boleto()->businessPerson()->build();

        $adapter = new BrazilFakeAdapter($payment, $config);
        $result = $adapter->transform();

        $validator = new JsonSchema\Validator;
        $validator->validate($result, $this->getSchema(['paymentSchema', 'brazilPaymentSchema']));

        $this->assertTrue($validator->isValid(), $this->getJsonMessage($validator));
    }
}

class BrazilFakeAdapter extends BrazilPaymentAdapter
{
}
