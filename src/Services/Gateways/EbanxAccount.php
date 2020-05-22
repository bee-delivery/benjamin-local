<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Bank;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Models\Currency;

class EbanxAccount extends Tef
{
    const API_TYPE = 'ebanxaccount';

    protected function getPaymentData(Payment $payment)
    {
        $payment->type = parent::API_TYPE;
        $payment->bankCode = Bank::EBANX_ACCOUNT;

        return parent::getPaymentData($payment);
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::USD,
        ];
    }
}
