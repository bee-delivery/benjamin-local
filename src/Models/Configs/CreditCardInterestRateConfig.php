<?php
namespace BeeDelivery\Benjamin\Models\Configs;

use BeeDelivery\Benjamin\Models\BaseModel;

class CreditCardInterestRateConfig extends BaseModel
{
    /**
     * Number of instalments
     *
     * @var integer
     */
    public $instalmentNumber;

    /**
     * Interest rate for this number of instalments
     *
     * @var float
     */
    public $interestRate = 0.0;
}
