<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\SafetyPayPaymentAdapter;

abstract class SafetyPay extends DirectGateway
{
    protected static function getEnabledCountries()
    {
        return [
            Country::ECUADOR,
            Country::PERU,
        ];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::PEN,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new SafetyPayPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
