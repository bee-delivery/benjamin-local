<?php
namespace Tests\Helpers\Providers\es_MX;

use BeeDelivery\Benjamin\Models\Country;
use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = Country::MEXICO;

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
