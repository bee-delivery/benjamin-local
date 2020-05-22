<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use BeeDelivery\Benjamin\Models\Configs\Config;
use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Services\Gateways\Servipag;
use BeeDelivery\Benjamin\Services\Http\Client;

class ServipagTest extends GatewayTestCase
{
    public function testPayment()
    {
        $servipagSuccessfulResponse = $this->getServipagSuccessfulResponseJson();
        $client = $this->getMockedClient($servipagSuccessfulResponse);

        $factory = new BuilderFactory('es_CL');
        $payment = $factory->payment()->build();
        $gateway = new Servipag($this->config, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithUSD()
    {
        $this->config->baseCurrency = Currency::USD;
        $gateway = new Servipag($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::CHILE,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $this->config->baseCurrency = Currency::USD;
        $gateway = new Servipag(new Config([
            'baseCurrency' => Currency::CLP,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::CHILE,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new Servipag(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function getServipagSuccessfulResponseJson()
    {
        return '{"redirect_url":"https:\/\/sandbox.ebanx.com\/ws\/directtefredirect\/?hash=592c83d8e658046969a23c1056da1a54276afff4f3cd2cb3","payment":{"hash":"592c83d8e658046969a23c1056da1a54276afff4f3cd2cb3","pin":"296386782","merchant_payment_code":"b784ee12a5230a3c9d070087267e6891","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-29 17:26:00","confirm_date":null,"transfer_date":null,"amount_br":"44933.00","amount_ext":"64.55","amount_iof":"0.00","currency_rate":"696.0900","currency_ext":"USD","due_date":"2017-06-01","instalments":"1","payment_type_code":"servipag","pre_approved":false,"capture_available":null,"user_value_5":"Benjamin","note":"Fake payment created by PHPUnit.","customer":{"document":"0","email":"alfaro.mara@loya.es.cl","name":"LUNA GRANADOS","birth_date":"1966-05-24"}},"status":"SUCCESS"}';
    }
}
