<?php
namespace BeeDelivery\Benjamin\Services\Gateways;

use BeeDelivery\Benjamin\Models\Configs\Config;
use BeeDelivery\Benjamin\Models\Payment;
use BeeDelivery\Benjamin\Models\Currency;
use BeeDelivery\Benjamin\Services\Http\Client;
use BeeDelivery\Benjamin\Services\Http\HttpService;
use BeeDelivery\Benjamin\Services\Exchange;

abstract class BaseGateway extends HttpService
{
    /**
     * @var Exchange
     */
    protected $exchange;

    protected static function getEnabledCountries()
    {
        throw new \BadMethodCallException('You should override this method.');
    }

    protected static function getEnabledCurrencies()
    {
        throw new \BadMethodCallException('You should override this method.');
    }

    public function __construct(Config $config, Client $client = null)
    {
        parent::__construct($config, $client);
        $this->exchange = new Exchange($this->config, $this->client);
    }

    /**
     * @param  string $country
     * @return boolean
     */
    public function isAvailableForCountry($country)
    {
        $siteCurrency = $this->config->baseCurrency;
        $globalCurrencies = Currency::globalCurrencies();
        $localCurrency = Currency::localForCountry($country);

        $countryIsAccepted = static::acceptsCountry($country);
        $currencyIsAccepted = static::acceptsCurrency($siteCurrency);
        $siteCurrencyIsGlobal = in_array($siteCurrency, $globalCurrencies);
        $siteCurrencyMatchesCountry = $siteCurrency === $localCurrency;

        return $countryIsAccepted
            && $currencyIsAccepted
            && ($siteCurrencyIsGlobal || $siteCurrencyMatchesCountry);
    }

    /**
     * @param  string $currency
     * @return bool
     */
    public static function acceptsCurrency($currency)
    {
        return in_array($currency, static::getEnabledCurrencies());
    }

    /**
     * @param  string $country
     * @return bool
     */
    public static function acceptsCountry($country)
    {
        return in_array($country, static::getEnabledCountries());
    }

    protected function availableForCountryOrThrow($country)
    {
        if ($this->isAvailableForCountry($country)) {
            return;
        }

        throw new \InvalidArgumentException(sprintf(
            'Gateway not available for %s%s',
            $country,
            Currency::isGlobal($this->config->baseCurrency)
                ? ''
                : ' using '.$this->config->baseCurrency
        ));
    }
}
