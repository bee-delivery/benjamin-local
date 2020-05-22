<?php
namespace Tests\Unit\Services\Http;

use BeeDelivery\Benjamin\Models\Configs\Config;
use BeeDelivery\Benjamin\Services\Http\Client;
use BeeDelivery\Benjamin\Services\Http\HttpService;
use Tests\TestCase;

class HttpServiceTest extends TestCase
{
    public function testSwitchMode()
    {
        $config = new Config();
        $client = new Client();
        $http = new TestHttpService($config, $client);

        $this->assertEquals(Client::MODE_SANDBOX, $client->getMode());
        $http->changeToLive();
        $this->assertEquals(Client::MODE_LIVE, $client->getMode());
    }
}

class TestHttpService extends HttpService
{
    public function changeToLive()
    {
        $this->switchMode(false);
    }
}
