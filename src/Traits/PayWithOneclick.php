<?php

namespace SextaNet\LaravelOneclick\Traits;

use SextaNet\LaravelOneclick\Exceptions\MissingOneclickParentId;
use SextaNet\LaravelOneclick\LaravelOneclick;
use SextaNet\LaravelOneclick\Models\OneclickCard;
use Transbank\Webpay\Oneclick;

trait PayWithOneclick
{
    public function getOneclickParentId(): string
    {
        return $this->id ?? throw new MissingOneclickParentId;
    }

    public function payWithOneclick(OneclickCard $oneclick_card, int $installments_number = 0, $mall_code = null)
    {
        if (! $mall_code) {
            $mall_code = get_mall_code();
        }

        $details = [
            // [
            //     'amount' => $this->amount,
            //     'buy_order' => $this->id,
            //     'installments_number' => $installments_number,
            //     'commerce_code' => Oneclick::DEFAULT_CHILD_COMMERCE_CODE_1,
            // ],
            [
                'amount' => $this->amount,
                'buy_order' => $this->id,
                'installments_number' => $installments_number,
                'commerce_code' => $mall_code,
            ],
        ];

        return LaravelOneclick::payAndStore($this, $oneclick_card, $details);
    }
}
