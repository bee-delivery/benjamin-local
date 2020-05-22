<?php
namespace Tests\Helpers\Providers\es_CL;

use Tests\Helpers\Providers\Person as BasePerson;

class Person extends BasePerson
{
    /**
     * @return \BeeDelivery\Benjamin\Models\Person
     */
    public function personModel()
    {
        $person = parent::personModel();
        $person->email .= '.cl';
        return $person;
    }
}
