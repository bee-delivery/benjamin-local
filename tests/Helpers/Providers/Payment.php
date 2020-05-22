<?php
namespace Tests\Helpers\Providers;

use BeeDelivery\Benjamin\Models\Payment as PaymentModel;

class Payment extends BaseProvider
{
    /**
     * @return \BeeDelivery\Benjamin\Models\Payment
     */
    public function paymentModel()
    {
        $payment = new PaymentModel();
        $payment->address = $this->faker->addressModel();
        $payment->person = $this->faker->personModel();
        $payment->deviceId = $this->faker->sha256;
        $payment->merchantPaymentCode = md5(time());
        $payment->note = 'Fake payment created by PHPUnit.';
        $payment->items = $this->faker->itemModels($this->faker->randomDigitNotNull());
        $payment->amountTotal = array_reduce($payment->items, function ($carry, $item) {
            $carry += $item->unitPrice * $item->quantity;
            return $carry;
        });

        return $payment;
    }
}
