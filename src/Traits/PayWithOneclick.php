<?php

namespace SextaNet\LaravelOneclick\Traits;

use SextaNet\LaravelOneclick\Models\OneclickCard;
use SextaNet\LaravelOneclick\Models\OneclickTransaction;
use Transbank\Webpay\Oneclick;

trait PayWithOneclick
{
    public function payWithOneclick(OneclickCard $oneclick_card, int $installments_number = 1)
    {
        $app_name = str()->of(config('app.name'))->replace(' ', '-')->lower();
        $app_env = config('app.env');
        $parent_order = $app_name.'-'.$app_env.'-'.$this->id;

        $details = [
            [
                'amount' => $this->amount,
                'buy_order' => $this->id,
                'installments_number' => $installments_number,
                'commerce_code' => Oneclick::DEFAULT_CHILD_COMMERCE_CODE_1,
            ],
            [
                'amount' => $this->amount,
                'buy_order' => $this->id,
                'installments_number' => $installments_number,
                'commerce_code' => Oneclick::DEFAULT_CHILD_COMMERCE_CODE_1,
            ],
        ];

        $result = $oneclick_card->pay($parent_order, $details);

        dd($result);

        $converted = format_transaction_response($result);

        dd(OneclickTransaction::create($converted));
    }
}
