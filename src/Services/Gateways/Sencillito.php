<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\CashPaymentAdapter;

class Sencillito extends DirectGateway
{
    const API_TYPE = 'sencillito';

    protected static function getEnabledCountries()
    {
        return [Country::CHILE];
    }
    protected static function getEnabledCurrencies()
    {
        return [
            Currency::CLP,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $adapter = new CashPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
