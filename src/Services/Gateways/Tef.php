<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\TefPaymentAdapter;

class Tef extends DirectGateway
{
    const API_TYPE = 'tef';

    protected static function getEnabledCountries()
    {
        return [Country::BRAZIL];
    }
    protected static function getEnabledCurrencies()
    {
        return [
            Currency::BRL,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new TefPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
