<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Country;
use BeeDelivery\Benjamin\Models\Currency;

use BeeDelivery\Benjamin\Models\Request;
use BeeDelivery\Benjamin\Services\Adapters\RequestAdapter;

class Hosted extends BaseGateway
{
    /**
     * @param  Request $request
     * @return array
     */
    public function create(Request $request)
    {
        return $this->client->request($this->getPaymentData($request));
    }

    protected static function getEnabledCountries()
    {
        return Country::all();
    }

    protected static function getEnabledCurrencies()
    {
        return Currency::all();
    }

    protected function getPaymentData(Request $request)
    {
        $adapter = new RequestAdapter($request, $this->config);
        return $adapter->transform();
    }
}
