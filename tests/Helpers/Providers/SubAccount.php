<?php
namespace Tests\Helpers\Providers;

use BeeDelivery\Benjamin\Models\SubAccount as SubAccountModel;

class SubAccount extends BaseProvider
{
    /**
     * @return \BeeDelivery\Benjamin\Models\SubAccount
     */
    public function subAccountModel()
    {
        $subAccount = new SubAccountModel();
        $subAccount->name = $this->faker->name;
        $subAccount->imageUrl = $this->faker->url;

        return $subAccount;
    }
}
