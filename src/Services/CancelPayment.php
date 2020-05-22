<?php
namespace BeeDelivery\Benjamin\Services;

use BeeDelivery\Benjamin\Services\Adapters\CancelAdapter;
use BeeDelivery\Benjamin\Services\Http\HttpService;

class CancelPayment extends HttpService
{
    /**
     * @param $hash
     *
     * @return array
     */
    public function request($hash)
    {
        $adapter = new CancelAdapter($hash, $this->config);
        $response = $this->client->cancel($adapter->transform());

        return $response;
    }
}
