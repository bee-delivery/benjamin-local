<?php
namespace Tests\Helpers\Providers\es_CO;

use Tests\Helpers\Providers\Person as BasePerson;

class Person extends BasePerson
{
    /**
     * @return \BeeDelivery\Benjamin\Models\Person
     */
    public function personModel()
    {
        $person = parent::personModel();
        $person->email .= '.co';
        return $person;
    }
}
