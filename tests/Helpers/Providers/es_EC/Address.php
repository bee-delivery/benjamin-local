<?php
namespace Tests\Helpers\Providers\es_EC;

use BeeDelivery\Benjamin\Models\Country;
use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = Country::ECUADOR;

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
