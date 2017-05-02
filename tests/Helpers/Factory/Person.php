<?php

namespace Tests\Helpers\Factory;

use Ebanx\Benjamin\Models\Person as PersonModel;

class Person extends BaseFactory
{
    public static function valid($type = 'personal')
    {
        $faker = self::faker();

        $person = new PersonModel();
        $person->birthdate = $faker->dateTimeBetween('-40 years', '-18 years');
        $person->email = $faker->email;
        $person->ip = $faker->ipv4;
        $person->name = $faker->name;
        $person->phoneNumber = $faker->phoneNumber;

        switch ($type) {
            case 'business':
                $person->personType = PersonModel::TYPE_BUSINESS;
                $person->document = $faker->cnpj(false);
                break;
            case 'personal':
            default:
                $person->document = $faker->cpf(false);
                break;
        }

        return $person;
    }
}