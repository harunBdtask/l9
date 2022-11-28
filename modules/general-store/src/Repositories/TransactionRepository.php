<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvTransaction;

class TransactionRepository extends Repository
{
    public function itemTransactionsUntil($itemId, $deliveryDate)
    {
        $firstJan = Carbon::today()
            ->firstOfYear()
            ->toDateString();

        return GsInvTransaction::where('item_id', $itemId)
            ->select(DB::raw('*, qty * rate as total'))
            ->whereBetween('trn_date', [$firstJan, $deliveryDate])
            ->get();

    }

    /**
     * @return string
     */
    public function model()
    {
        return GsInvTransaction::class;
    }

}
