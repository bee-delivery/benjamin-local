<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\CashPaymentAdapter;
use BeeDelivery\Benjamin\Services\Traits\Printable;

class OtrosCupones extends DirectGateway
{
    use Printable;

    const API_TYPE = 'cupon';

    protected static function getEnabledCountries()
    {
        return [Country::ARGENTINA];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::ARS,
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
        return 'https://%s.ebanx.com.br/print/voucher/?hash=%s';
    }
}
