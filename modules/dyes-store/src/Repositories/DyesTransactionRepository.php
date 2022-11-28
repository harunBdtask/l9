<?php

namespace SkylarkSoft\GoRMG\DyesStore\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;

class DyesTransactionRepository extends Repository
{
    public function itemTransactionsUntil(int $itemId, int $lifeEndDays, int $storeId, $deliveryDate)
    {
        $firstJan = Carbon::today()->firstOfYear()->toDateString();

        return DyesChemicalTransaction::where('item_id', $itemId)
            ->where('life_end_days', $lifeEndDays)
            ->when($storeId == null, function ($query) {
                $query->whereNull('trn_store');
            })
            ->when($storeId != null, function ($query) use ($storeId) {
                $query->where('trn_store', $storeId);
            })
            ->select(DB::raw('*, qty * rate as total'))
            ->whereBetween('trn_date', [$firstJan, $deliveryDate])
            ->get();
    }

    public function model(): string
    {
        return DyesChemicalTransaction::class;
    }
}
