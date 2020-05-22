<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\EftPaymentAdapter;

class Eft extends DirectGateway
{
    const API_TYPE = 'eft';

    protected static function getEnabledCountries()
    {
        return [Country::COLOMBIA];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::COP,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new EftPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
