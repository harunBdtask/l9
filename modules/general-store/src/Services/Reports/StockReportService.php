<?php


namespace SkylarkSoft\GoRMG\GeneralStore\Services\Reports;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvItemCategory;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvTransaction;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;

class StockReportService
{
    public static function data($store, $firstDate, $lastDate, $type): array
    {
        $items = GsItem::with("uomDetails")->orderBy("name", "ASC");
        $total = [
            'total_opening_value' => 0,
            'total_inward_value' => 0,
            'total_outward_value' => 0,
            'total_closing_value' => 0,
        ];
        $items = $type !== "excel" ? $items->paginate() : $items->get();
        if ($type !== "excel" && $type !== "pdf" && $items->currentPage() === $items->lastPage()) {
            $stockSummeryItems = GsItem::with("uomDetails")->orderBy("name", "ASC")->get();
            collect($stockSummeryItems)->each(function ($item) use (&$total, $firstDate, $lastDate) {
                $stockDetails = $item->stock($firstDate, $lastDate);
                $total['total_opening_value'] += $stockDetails['opening_value'];
                $total['total_inward_value'] += $stockDetails['inward_value'];
                $total['total_outward_value'] += $stockDetails['outward_value'];
                $total['total_closing_value'] += $stockDetails['closing_value'];
            });
        }
        $responseData['items'] = $items;
        $responseData['store'] = $store;
        $responseData['store_name'] = get_store_name($store);
        $responseData['first_date'] = $firstDate;
        $responseData['last_date'] = $lastDate;
        $responseData['type'] = $type;
        $responseData['total'] = $total;
        return $responseData;
    }

    public static function categoryWiseData($store, $firstDate, $lastDate, $type, $category)
    {
        $items = GsItem::with("uomDetails")->orderBy("name", "ASC")->get();
        $total = [
            'total_opening_value' => 0,
            'total_inward_value' => 0,
            'total_outward_value' => 0,
            'total_closing_value' => 0,
        ];
        $categories = GsInvItemCategory::has('items')->orderBy('name', 'ASC');
        if ($category) {
            $categories = $categories->Filter($category);
        }
        $categories = $type !== "excel" ? $categories->paginate(1) : $categories->get();
        if ($type !== "excel" && $type !== "pdf" && $categories->currentPage() === $categories->lastPage()) {
            collect($items)->each(function ($item) use (&$total, $firstDate, $lastDate) {
                $stockDetails = $item->stock($firstDate, $lastDate);
                $total['total_opening_value'] += $stockDetails['opening_value'];
                $total['total_inward_value'] += $stockDetails['inward_value'];
                $total['total_outward_value'] += $stockDetails['outward_value'];
                $total['total_closing_value'] += $stockDetails['closing_value'];
            });
        }

        $responseData['items'] = $items;
        $responseData['type'] = $type;
        $responseData['categories'] = $categories;
        $responseData['first_date'] = $firstDate;
        $responseData['last_date'] = $lastDate;
        $responseData['store'] = $store;
        $responseData['category'] = $category;
        $responseData['store_name'] = get_store_name($store);
        $responseData['total'] = $total;

        return $responseData;
    }

    public static function itemWiseReport($store, $firstDate, $lastDate, $type, $itemId)
    {
        if ($itemId) {
            $item = GsItem::with("uomDetails")->where('id', $itemId)->first();
            $itemTransactionDate = GsInvTransaction::where('item_id', $itemId)
                ->where('trn_date', '>=', Carbon::parse($firstDate))
                ->where('trn_date', '<=', Carbon::parse($lastDate))
                ->groupBy('trn_date')
                ->get()->pluck('trn_date');
        }

        $responseData['first_date'] = $firstDate;
        $responseData['last_date'] = $lastDate;
        $responseData['store'] = $store;
        $responseData['item'] = $item ?? null;
        $responseData['item_transaction_date'] = $itemTransactionDate ?? null;
        $responseData['itemId'] = $itemId;
        $responseData['store_name'] = get_store_name($store);
        $responseData['type'] = $type;

        return $responseData;
    }


    private static function transactionsInDateRange($store, $firstDate, $lastDate)
    {
        return GsInvTransaction::where('store', $store)
            ->with("item.uomDetails:id,name")
            ->whereBetween('trn_date', [$firstDate, $lastDate])
            ->select(DB::raw('*, qty * rate as total'))
            ->get()->toArray();
    }


    private static function calculateOpeningQty($data): int
    {
        $transactions = collect($data);
        $totalOut = $transactions->where('trn_type', 'out')->sum('qty') ?: 0;
        $totalIn = $transactions->where('trn_type', 'in')->sum('qty') ?: 0;
        return $totalIn - $totalOut;
    }

    private static function calculateOutRate($data): float
    {
        if (!count($data)) {
            return 0;
        }
        $transactions = collect($data);
        $totalOut = $transactions->where('trn_type', 'out')->sum('total') ?: 0;
        $totalIn = $transactions->where('trn_type', 'in')->sum('total') ?: 0;
        $inQty = $transactions->where('trn_type', 'in')->sum('qty') ?: 0;
        $outQty = $transactions->where('trn_type', 'out')->sum('qty') ?: 0;
        return round(($totalIn - $totalOut) / ($inQty - $outQty), 2);
    }
}
