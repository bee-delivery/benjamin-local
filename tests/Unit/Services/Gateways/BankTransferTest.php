<?php
namespace Tests\Unit\Services\Gateways;

use Tests\Helpers\Builders\BuilderFactory;

use BeeDelivery\Benjamin\Models\Configs\Config;
use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Services\Gateways\BankTransfer;
use BeeDelivery\Benjamin\Services\Http\Client;
use Tests\Helpers\Mocks\Http\ClientForTests;

class BankTransferTest extends GatewayTestCase
{
    public function testBusinessPersonPayment()
    {
        $bankTransferSuccessfulResponse = $this->getBankTransferSuccessfulResponseJson();
        $client = $this->getMockedClient($bankTransferSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->banktransfer()->businessPerson()->build();
        $gateway = $this->getTestGateway($client);

        $result = $gateway->create($payment);
        $this->assertArrayHasKey('payment', $result);
    }

    public function testBusinessPersonRequest()
    {
        $bankTransferSuccessfulResponse = $this->getBankTransferSuccessfulResponseJson();
        $client = $this->getMockedClient($bankTransferSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->banktransfer()->businessPerson()->build();
        $gateway = $this->getTestGateway($client);

        $result = $gateway->request($payment);
        $this->assertArrayHasKey('payment', $result);
    }

    public function testAvailabilityWithUSD()
    {
        $this->config->baseCurrency = Currency::USD;
        $gateway = new BankTransfer($this->config);

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $gateway = new BankTransfer(new Config([
            'baseCurrency' => Currency::BRL,
        ]));

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $gateway = new BankTransfer(new Config([
            'baseCurrency' => Currency::MXN,
        ]));

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testSandboxTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://staging.ebanx.com.br/print/voucher/execute?hash=591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4',
            $gateway->getUrl('591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4')
        );
    }

    public function testLiveTicketUrl()
    {
        $gateway = $this->getTestGateway();
        $this->assertEquals(
            'https://print.ebanx.com.br/print/voucher/execute?hash=591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4',
            $gateway->getUrl('591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4', false)
        );
    }

    public function getBankTransferSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4","pin":"670071563","merchant_payment_code":"248b2672f000e293268be28d6048d600","order_number":null,"status":"PE","status_date":null,"open_date":"2017-05-16 19:42:05","confirm_date":null,"transfer_date":null,"amount_br":"48.81","amount_ext":"48.63","amount_iof":"0.18","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2018-11-22","instalments":"1","payment_type_code":"banktransfer","boleto_url":"https:\/\/sandbox.ebanx.com\/print\/voucher\/execute?hash=591b803da5549b6a1bac524b31e6eef55c2e67af8e40e1e4","boleto_barcode":"34191760071244348372714245740007871600000004881","boleto_barcode_raw":"34198716000000048811760012443483721424574000","pre_approved":false,"capture_available":null,"customer":{"document":"40701766000118","email":"sdasneves@r7.com","name":"SR GUSTAVO FERNANDO VALENCIA","birth_date":"1978-03-28"}},"status":"SUCCESS"}';
    }

    /**
     * @param Client $client
     * @return BankTransferForTests
     */
    private function getTestGateway(Client $client = null)
    {
        $gateway = new BankTransfer($this->config, $client);
        return $gateway;
    }
}
