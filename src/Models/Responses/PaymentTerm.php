<?php
namespace BeeDelivery\Benjamin\Models\Responses;

use BeeDelivery\Benjamin\Models\BaseModel;

class PaymentTerm extends BaseModel
{
    /**
     * @var integer
     */
    public $instalmentNumber;

    /**
     * @var float
     */
    public $baseAmount;

    /**
     * @var float
     */
    public $localAmountWithTax;

    /**
     * @var boolean
     */
    public $hasInterests;
}
