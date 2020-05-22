<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\CardPaymentAdapter;

class DebitCard extends DirectGateway
{
    const API_TYPE = 'debitcard';

    protected static function getEnabledCountries()
    {
        return [
            Country::MEXICO,
        ];
    }
    protected static function getEnabledCurrencies()
    {
        return [
            Currency::MXN,
            Currency::USD,
            Currency::EUR,
        ];
    }

    protected function getPaymentData(Payment $payment)
    {
        $payment->card->type = self::API_TYPE;

        $adapter = new CardPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
