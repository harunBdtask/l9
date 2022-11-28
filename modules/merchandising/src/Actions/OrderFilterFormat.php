<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use App\Constants\ApplicationConstant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class OrderFilterFormat

{

    public function handleAll($search, $sort): Collection
    {
        return $this->searchQuery($search)->orderBy('id', $sort)->get();
    }

    public function handle($search, $sort, $page = null, $paginateNumber): LengthAwarePaginator
    {
        return $this->searchQuery($search)->orderBy('id', $sort)->paginate($paginateNumber, ['*'], 'page', $page);
    }

    private function searchQuery($search)
    {
        $uom = array_search(ucfirst($search), PriceQuotation::STYLE_UOM) ?? $search;
        return Order::query()
            ->factoryWiseFilter()
            ->with([
                'factory:id,factory_name,factory_short_name',
                'buyer:id,name',
                'productCategory:id,category_name',
                'teamLeader',
                'currency',
                'season',
                'dealingMerchant:id,screen_name',
                'factoryMerchant:id,screen_name',
                'productDepartment:id,product_department',
                'purchaseOrders'
            ])
            ->when(request()->query('from_date') && request()->query('to_date'), function ($query) {
                $query->whereBetween('created_at', [request()->query('from_date'), request()->query('to_date')]);
            })
            ->when($search, function ($query) use ($search, $uom) {
                $query->where('job_no', 'like', '%' . $search . '%')
                    ->orWhere('style_name', 'like', '%' . $search . '%')
                    ->orWhere('order_uom_id', $uom)
                    ->orWhere('smv', 'like', '%' . $search . '%')
                    ->orWhere('reference_no', 'like', '%' . $search . '%')
                    ->orWhereHas('teamLeader', function ($query) use ($search) {
                        $query->where('screen_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('productDepartment', function ($query) use ($search) {
                        $query->where('product_department', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('dealingMerchant', function ($query) use ($search) {
                        $query->where('first_name', 'like', '%' . $search . '%');
                        $query->orWhere('last_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('factoryMerchant', function ($query) use ($search) {
                        $query->where('first_name', 'like', '%' . $search . '%');
                        $query->orWhere('last_name', 'like', '%' . $search . '%');
                        $query->orWhere('screen_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('factoryMerchant', function ($query) use ($search) {
                        $query->where('first_name', 'like', '%' . $search . '%');
                        $query->orWhere('last_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->whereIn('id', $this->userWiseBuyer());
                    })
                    ->orWhereHas('season', function ($query) use ($search) {
                        $query->where('season_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('currency', function ($query) use ($search) {
                        $query->where('currency_name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('purchaseOrders', function ($query) use ($search) {
                        $query->where('po_no', $search)
                            ->orWhere('comm_file_no', $search);
                    });
            })
            ->withCount('purchaseOrders')
            ->withSum('purchaseOrders as total_po_quantity', 'po_quantity');
    }

    private function userWiseBuyer(): Collection
    {
        if ($this->isAdmin()) {
            return Buyer::all()->pluck('id');
        }
        $buyerPermission = Session::get('buyerPermission');

        $viewBuyerPermission = Session::get('viewBuyerPermission');

        return collect($buyerPermission)->merge($viewBuyerPermission)->unique()->values();
    }

    private function isAdmin(): bool
    {
        $role = getRole();
        return in_array($role, [
            ApplicationConstant::SUPER_ADMIN,
            ApplicationConstant::ADMIN,
            ApplicationConstant::MERCHANDISER,
        ]);
    }
}
