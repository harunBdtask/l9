<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;

class BudgetFilterFormat
{
    public function handleAll($search, $sort = 'desc'): Collection
    {
        return $this->searchQuery($search)->orderBy('id', $sort)->get();
    }

    public function handle($search, $paginateNumber , $sort = 'desc', $page = null): LengthAwarePaginator
    {
        return $this->searchQuery($search)->orderBy('id', $sort)->paginate($paginateNumber, ['*'], 'page', $page);
    }

    private function searchQuery($search)
    {
        $from_date = request()->query('from_date') ?? null;
        $to_date = request()->query('to_date') ?? null;
        $uomValue = strtolower($search);
        $uom = array_search(ucfirst($uomValue), PriceQuotation::STYLE_UOM) ?? $search;
        $time = collect(explode('/', $search))->implode('-');
        $date = strtotime($time) ? Carbon::parse($time)->format('Y-m-d') : $search;
        return Budget::with('productDepartment','order.purchaseOrders.poDetails','createdBy')
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                $query->whereBetween('created_at', [$from_date, $to_date]);
            })
            ->when($search, function ($query) use ($search, $date, $uom) {
                $query->withSum('purchaseOrders as total_po_quantity', 'po_quantity')
                    ->where('job_no', 'like', '%' . $search . '%')
                    ->orWhere('order_uom_id', $uom)
                    ->orWhereDate('costing_date', 'LIKE', $date)
                    ->orWhereDate('approve_date', "LIKE", $date)
                    ->orWhere('style_name', 'like', '%' . $search . '%')
                    ->orWhere('job_qty', 'like', '%' . $search . '%')
                    ->orWhere('region', 'like', '%' . $search . '%')
                    ->orWhere('machine_line', 'like', '%' . $search . '%')
                    ->orWhere('incoterm_place', 'like', '%' . $search . '%')
                    ->orWhereHas('productDepartment', function ($query) use ($search) {
                        $query->where('product_department', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('order', function ($query) use ($search) {
                        $query->where('reference_no', 'like', '%' . $search . '%');
                    });
            });

    }
}
