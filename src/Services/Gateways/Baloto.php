<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\CashPaymentAdapter;
use BeeDelivery\Benjamin\Services\Traits\Printable;

class Baloto extends DirectGateway
{
    use Printable;

    const API_TYPE = 'baloto';

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
        $adapter = new CashPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return 'https://%s.ebanx.com.br/print/baloto/?hash=%s';
    }
}
