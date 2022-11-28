<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use Exception;

class YarnStockSummaryService
{
    /**
     * @param $yarn
     * @param $balanceCheck
     * @return Builder|Model|mixed|object|null
     */
    public function summary($yarn, $balanceCheck = null)
    {
        return YarnStockSummary::query()
            ->when($balanceCheck, function ($query) {
                $query->where('balance', '>', 0);
            })
            ->where('yarn_count_id', $yarn['yarn_count_id'])
            ->where('yarn_composition_id', $yarn['yarn_composition_id'])
            ->where('yarn_type_id', $yarn['yarn_type_id'])
            ->where('yarn_lot', $yarn['yarn_lot'])
            ->where('uom_id', $yarn['uom_id'])
            ->where('yarn_color', $yarn['yarn_color'])
            ->where('yarn_brand', $yarn['yarn_brand'])
            ->where('store_id', $yarn['store_id'])
            ->first();
    }

    public function balance($yarn): array
    {
        try {
            $summary = $this->summary($yarn);
            return ['balance' => $summary->balance, 'balance_amount' => $summary->balance_amount];
        } catch (Exception $exception) {
            return ['balance' => 0, 'balance_amount' => 0];
        }
    }

    public function create($yarn): YarnStockSummary
    {
        $amount = $yarn['receive_qty'] * $yarn['rate'];

        $stockSummary = new YarnStockSummary([
            'store_id'            => $yarn['store_id'],
            'yarn_count_id'       => $yarn['yarn_count_id'],
            'yarn_composition_id' => $yarn['yarn_composition_id'],
            'yarn_type_id'        => $yarn['yarn_type_id'],
            'yarn_color'          => $yarn['yarn_color'],
            'yarn_lot'            => $yarn['yarn_lot'],
            'uom_id'              => $yarn['uom_id'],
            /*
             * In the beginning receive_amount is balance_amount and
             * receive_qty is balance
            */
            'receive_qty'         => $yarn['receive_qty'],
            'balance'             => $yarn['receive_qty'],
            'receive_amount'      => $amount,
            'balance_amount'      => $amount,
        ]);

        $stockSummary->save();

        return $stockSummary;
    }


    public function yarnRate($yarn, $summary = null)
    {
        if ($summary) {
            /* Stock Summary Data */
            $qty = $summary->balance;
            $amount = $summary->balance_amount;
            $receiveQty = $summary->receive_qty;
            $receiveAmount = $summary->receive_amount;
            $rate = $qty > 0
                ? round($amount / $qty, 4)
                : ($receiveQty > 0 ? round($receiveAmount / $receiveQty, 4) : 0);
        }
        else {
            /* Receive Detail Data */
            $query = YarnReceiveDetail::query()->find($yarn['id']);
            $rate = $query ? $query->rate : 0;
        }

        return round($rate, 4);
    }


    public function getYarnRate($yarn): float
    {
        $summary = $this->summary($yarn);

        return $this->yarnRate($yarn, $summary);
    }
}
