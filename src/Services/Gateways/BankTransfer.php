<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Services\Adapters\BankTransferPaymentAdapter;
use BeeDelivery\Benjamin\Services\Traits\Printable;

class BankTransfer extends DirectGateway
{
    use Printable;

    const API_TYPE = 'banktransfer';

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

    /**
     * @return string
     */
    protected function getUrlFormat()
    {
        return 'https://%s.ebanx.com.br/print/voucher/execute?hash=%s';
    }

    /**
     * @param Payment $payment
     * @return object
     */
    protected function getPaymentData(Payment $payment)
    {
        $adapter = new BankTransferPaymentAdapter($payment, $this->config);
        return $adapter->transform();
    }
}
