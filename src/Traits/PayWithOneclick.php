<?php

namespace SextaNet\LaravelOneclick\Traits;

use SextaNet\LaravelOneclick\Models\OneclickCard;
use SextaNet\LaravelOneclick\Models\OneclickTransaction;
use Transbank\Webpay\Oneclick;

trait PayWithOneclick
{
    public function payWithOneclick(OneclickCard $oneclick_card, int $installments_number = 0)
    {
        $parent_order = generate_oneclick_parent_id($this->id);

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
                'commerce_code' => Oneclick::DEFAULT_CHILD_COMMERCE_CODE_1,
            ],
        ];

        $result = $oneclick_card->pay($parent_order, $details);

        // dd($result);

        $converted = format_transaction_response($result);

        $transaction = $oneclick_card->transactions()->create($converted);

        dd($transaction);

        // dd(OneclickTransaction::create($converted));
    }
}
