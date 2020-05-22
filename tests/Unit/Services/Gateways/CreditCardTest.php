<?php
namespace Tests\Unit\Services\Gateways;

use BeeDelivery\Benjamin\Models\Responses\PaymentTerm;
use Tests\Helpers\Builders\BuilderFactory;

use BeeDelivery\Benjamin\Models\Configs\CreditCardConfig;
use BeeDelivery\Benjamin\Models\Configs\Config;
use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;

use BeeDelivery\Benjamin\Services\Gateways\CreditCard;
use BeeDelivery\Benjamin\Services\Http\Client;

class CreditCardTest extends GatewayTestCase
{
    public function testBusinessPersonPayment()
    {
        $creditCardConfig = new CreditCardConfig();

        $creditCardSuccessfulResponse = $this->getCreditCardSuccessfulResponseJson();
        $client = $this->getMockedClient($creditCardSuccessfulResponse);

        $factory = new BuilderFactory('pt_BR');
        $payment = $factory->payment()->creditCard()->businessPerson()->build();
        $gateway = new CreditCard($this->config, $creditCardConfig, $client);

        $result = $gateway->create($payment);

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testCaptureByHash()
    {
        $response = '{"payment":{"hash":"593edc391aca7d44c51928295946d95b24360f4afa61fb1d","pin":"887838438","merchant_payment_code":"43-b08597ff128f43a3335abf24ff3b5d08","order_number":"43","status":"CO","status_date":"2017-06-12 15:25:29","open_date":"2017-06-12 15:23:53","confirm_date":"2017-06-12 15:25:29","transfer_date":null,"amount_br":"301.14","amount_ext":"300.00","amount_iof":"1.14","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2017-06-12","instalments":"1","payment_type_code":"visa","transaction_status":{"acquirer":"EBANX","code":"OK","description":"Sandbox - Test credit card, transaction captured"},"pre_approved":true,"capture_available":false,"user_value_1":"from_woocommerce","user_value_3":"version=1.13.0","customer":{"document":"35433160874","email":"guilherme.pressutto+br@ebanx.com","name":"GUILHERME PRESSUTTO","birth_date":"1995-12-13"}},"status":"SUCCESS"}';
        $client = $this->getMockedClient($response);

        $creditCardConfig = new CreditCardConfig();
        $gateway = new CreditCard($this->config, $creditCardConfig, $client);

        $result = $gateway->captureByHash('593edc391aca7d44c51928295946d95b24360f4afa61fb1d');

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testCaptureByMerchantPaymentCode()
    {
        $response = '{"payment":{"hash":"593edc391aca7d44c51928295946d95b24360f4afa61fb1d","pin":"887838438","merchant_payment_code":"43-b08597ff128f43a3335abf24ff3b5d08","order_number":"43","status":"CO","status_date":"2017-06-12 15:25:29","open_date":"2017-06-12 15:23:53","confirm_date":"2017-06-12 15:25:29","transfer_date":null,"amount_br":"301.14","amount_ext":"300.00","amount_iof":"1.14","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2017-06-12","instalments":"1","payment_type_code":"visa","transaction_status":{"acquirer":"EBANX","code":"OK","description":"Sandbox - Test credit card, transaction captured"},"pre_approved":true,"capture_available":false,"user_value_1":"from_woocommerce","user_value_3":"version=1.13.0","customer":{"document":"35433160874","email":"guilherme.pressutto+br@ebanx.com","name":"GUILHERME PRESSUTTO","birth_date":"1995-12-13"}},"status":"SUCCESS"}';
        $client = $this->getMockedClient($response);

        $creditCardConfig = new CreditCardConfig();
        $gateway = new CreditCard($this->config, $creditCardConfig, $client);

        $result = $gateway->captureByMerchantPaymentCode('43-b08597ff128f43a3335abf24ff3b5d08');

        $this->assertArrayHasKey('payment', $result);

        // TODO: assert output (to be defined)
    }

    public function testAvailabilityWithLocalCurrency()
    {
        $creditCardConfig = new CreditCardConfig();

        $gateway = new CreditCard(new Config([
            'baseCurrency' => Currency::BRL,
        ]), $creditCardConfig);

        $this->assertAvailableForCountries($gateway, [
            Country::BRAZIL,
        ]);
    }

    public function testAvailabilityWithWrongLocalCurrency()
    {
        $creditCardConfig = new CreditCardConfig();

        $gateway = new CreditCard(new Config([
            'baseCurrency' => Currency::CLP,
        ]), $creditCardConfig);

        $this->assertNotAvailableAnywhere($gateway);
    }

    public function testGatewayDefaultMinimumInstalment()
    {
        $usdToBrlRate = 3.4743;
        $client = $this->getMockedClient($this->getExchangeRateSuccessfulResponseJsonWithRate($usdToBrlRate));

        $gateway = new CreditCard($this->config, new CreditCardConfig(), $client);

        $defaultMinInstalment = CreditCardConfig::acquirerMinInstalmentValueForCurrency(Currency::BRL);
        $country = Country::BRAZIL;
        $minInstalment = $gateway->getMinInstalmentValueForCountry($country);

        $this->assertEquals($defaultMinInstalment, $minInstalment);

        $gateway = new CreditCard($this->config, new CreditCardConfig([
            'minInstalmentAmount' => $defaultMinInstalment * 1.2
        ]), $client);

        $minInstalment = $gateway->getMinInstalmentValueForCountry($country);

        $this->assertNotEquals($defaultMinInstalment, $minInstalment);
    }

    public function testPaymentTermsBelowMinimumAmount()
    {
        $country = Country::BRAZIL;
        $value = CreditCardConfig::acquirerMinInstalmentValueForCurrency(Currency::localForCountry($country)) - 1;
        $gateway = $this->setupGateway(1, new Config());

        $paymentTerms = $gateway->getPaymentTermsForCountryAndValue($country, $value);
        $this->assertNotNull(
            $paymentTerms[0],
            'On spot payment should be allowed'
        );
    }

    public function testInstalmentsArgentina()
    {
        $country = Country::ARGENTINA;
        $usdToBrlRate = 1;
        $creditCard = $this->setupGateway($usdToBrlRate, new Config());
        $totalInstalments = 6;
        $instalmentsArray = $creditCard->getInstalmentsByCountry($country);
        $expectedInstalmentsArray = array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '6' => '6',
            '9' => '9',
            '12' => '12');

        $this->assertEquals($totalInstalments, count($instalmentsArray));
        $this->assertEquals($expectedInstalmentsArray, $instalmentsArray);
    }

    public function testInstalmentsBrazil()
    {
        $country = Country::BRAZIL;
        $totalInstalments = 12;
        $usdToBrlRate = 1;
        $creditCard = $this->setupGateway($usdToBrlRate, new Config());
        $instalmentsArray = $creditCard->getInstalmentsByCountry($country);
        $expectedInstalmentsArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $expectedInstalmentsArray[$i] = $i;
        }

        $this->assertEquals($totalInstalments, count($instalmentsArray));
        $this->assertEquals($expectedInstalmentsArray, $instalmentsArray);
    }

    public function testInstalmentsMexico()
    {
        $country = Country::MEXICO;
        $totalInstalments = 5;
        $usdToBrlRate = 1;
        $creditCard = $this->setupGateway($usdToBrlRate, new Config());
        $instalmentsArray = $creditCard->getInstalmentsByCountry($country);
        $expectedInstalmentsArray = array(
            '1' => '1',
            '3' => '3',
            '6' => '6',
            '9' => '9',
            '12' => '12');

        $this->assertEquals($totalInstalments, count($instalmentsArray));
        $this->assertEquals($expectedInstalmentsArray, $instalmentsArray);
    }

    public function testInstalmentsColombia()
    {
        $country = Country::COLOMBIA;
        $totalInstalments = 36;
        $usdToBrlRate = 1;
        $creditCard = $this->setupGateway($usdToBrlRate, new Config());
        $instalmentsArray = $creditCard->getInstalmentsByCountry($country);
        $expectedInstalmentsArray = [];
        for ($i = 1; $i <= 36; $i++) {
            $expectedInstalmentsArray[$i] = $i;
        }

        $this->assertEquals($totalInstalments, count($instalmentsArray));
        $this->assertEquals($expectedInstalmentsArray, $instalmentsArray);
    }

    public function testConfigMaxInstalments()
    {
        $country = Country::BRAZIL;
        $usdToBrlRate = 1;
        $creditCardConfig = new CreditCardConfig(['maxInstalments' => 1]);
        $creditCard = $this->setupGateway($usdToBrlRate, new Config(), $creditCardConfig);
        $instalmentsByCountry = $creditCard->getInstalmentsByCountry($country);
        $maxInstalmentsExpected = 1;

        $this->assertEquals($maxInstalmentsExpected, count($instalmentsByCountry));
    }

    public function testMaxInstalmentsWhenIsZero()
    {
        $country = Country::BRAZIL;
        $usdToBrlRate = 1;
        $creditCardConfig = new CreditCardConfig(['maxInstalments' => 0]);
        $creditCard = $this->setupGateway($usdToBrlRate, new Config(), $creditCardConfig);
        $instalmentsByCountry = $creditCard->getInstalmentsByCountry($country);
        $maxInstalmentsExpected = 1;

        $this->assertEquals($maxInstalmentsExpected, count($instalmentsByCountry));
    }

    public function testMaxInstalmentBiggerThanDefault()
    {
        $country = Country::BRAZIL;
        $usdToBrlRate = 1;
        $creditCardConfig = new CreditCardConfig(['maxInstalments' => 36]);
        $creditCard = $this->setupGateway($usdToBrlRate, new Config(), $creditCardConfig);
        $instalmentsByCountry = $creditCard->getInstalmentsByCountry($country);
        $maxInstalmentsExpected = 12;

        $this->assertEquals($maxInstalmentsExpected, count($instalmentsByCountry));
    }

    private function setupGateway($usdToBrlRate, $config, $creditCardConfig = null)
    {
        $client = $this->getMockedClient($this->getExchangeRateSuccessfulResponseJsonWithRate($usdToBrlRate));

        if (!$creditCardConfig) {
            $creditCardConfig = new CreditCardConfig();
        }
        return new CreditCard($config, $creditCardConfig, $client);
    }

    private function getCreditCardSuccessfulResponseJson()
    {
        return '{"payment":{"hash":"591f7a1cae81aaaade3f76014310da4a7289ab651e6ec44e","pin":"440297024","merchant_payment_code":"c1ef11f4be81d3515d2879d486718508","order_number":null,"status":"CA","status_date":"2017-05-19 20:05:00","open_date":"2017-05-19 20:04:59","confirm_date":null,"transfer_date":null,"amount_br":"48.81","amount_ext":"48.63","amount_iof":"0.18","currency_rate":"1.0000","currency_ext":"BRL","due_date":"2017-05-22","instalments":"1","payment_type_code":"mastercard","transaction_status":{"acquirer":"EBANX","code":"NOK","description":"Sandbox - Not a test card, transaction declined"},"pre_approved":false,"capture_available":false,"note":"Fake payment created by PHPUnit.","customer":{"document":"60639321000162","email":"ksalgado@furtado.org","name":"DR FRANCO MASCARENHAS SOBRINHO","birth_date":"1971-01-07"}},"status":"SUCCESS"}';
    }

    /**
     * @param PaymentTerm $paymentTerm
     * @param float       $originalValue
     * @param float       $interestRate
     */
    private function assertInterestInPaymentTerm(PaymentTerm $paymentTerm, $originalValue, $interestRate)
    {
        $hasInterestFailMessage = 'Failed to mark term ' . $paymentTerm->instalmentNumber . ' with interest flag accordingly';
        $interestCalcFailMessage = 'Failed to add interest to term ' . $paymentTerm->instalmentNumber;

        $ratio = 1 + $interestRate;
        $total = $paymentTerm->instalmentNumber * $paymentTerm->baseAmount;
        $crossCheck = $total / $ratio;

        $this->assertEquals($interestRate !== 0, $paymentTerm->hasInterests, $hasInterestFailMessage);
        $this->assertEquals($originalValue, $crossCheck, $interestCalcFailMessage);
    }
}
