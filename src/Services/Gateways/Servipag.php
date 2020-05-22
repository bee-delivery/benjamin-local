<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\EftPaymentAdapter;

class Servipag extends DirectGateway
{
    const API_TYPE = 'servipag';

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
        $adapter = new EftPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
