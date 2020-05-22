<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\BoletoPaymentAdapter;
use BeeDelivery\Benjamin\Services\Traits\Printable;

class Boleto extends DirectGateway
{
    use Printable;

    const API_TYPE = 'boleto';

    protected static function getEnabledCountries()
    {
        return [Country::BRAZIL];
    }

    protected static function getEnabledCurrencies()
    {
        return [
            Currency::BRL,
        ];
    }

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return 'https://%s.ebanx.com.br/print/?hash=%s';
    }

    /**
     * @param Payment $payment
     * @return object
     */
    protected function getPaymentData(Payment $payment)
    {
        $adapter = new BoletoPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
