<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

class CreditCardTest extends TestGateway
{
    public function testBusinessPersonPayment()
    {
        $client = $this->getMockedClient('{"payment":{"hash":"591f7a1cae81aaaade3f76014310da4a7289ab651e6ec44e","pin":"440297024","merchant_payment_code":"c1ef11f4be81d3515d2879d486718508","order_number":null,"status":"CA","status_date":"2017-05-19 20:05:00","open_date":"2017-05-19 20:04:59","confirm_date":null,"transfer_date":null,"amount_br":"48.81","amount_ext":"48.63","amount_iof":"0.18","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2017-05-22","instalments":"1","payment_type_code":"mastercard","transaction_status":{"acquirer":"EBANX","code":"NOK","description":"Sandbox - Not a test card, transaction declined"},"pre_approved":false,"capture_available":false,"note":"Fake payment created by PHPUnit.","customer":{"document":"60639321000162","email":"ksalgado@furtado.org","name":"DR FRANCO MASCARENHAS SOBRINHO","birth_date":"1971-01-07"}},"status":"SUCCESS"}');

        $payment = BuilderFactory::payment()->creditCard()->businessPerson()->build();
        $result = Benjamin($this->config)->gateways()->creditCard()->create($payment, $client);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }
}