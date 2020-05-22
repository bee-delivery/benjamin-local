<?php
namespace Tests\Helpers\Providers\es_CL;

use BeeDelivery\Benjamin\Models\Country;
use Tests\Helpers\Providers\Address as BaseAddress;

class Address extends BaseAddress
{
    public function addressModel()
    {
        $model = parent::addressModel();
        $model->country = Country::CHILE;

        return $model;
    }

    public function stateAbbr()
    {
        return '';
    }
}
