<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
class PriceQuotationFilterFormat

{

    public function handleAll($search, $sort): Collection
    {
        return $this->searchQuery($search)->orderBy('id', $sort)->get();
    }

    public function handle($search, $sort, $page = null,$paginateNumber): LengthAwarePaginator
    {
        return $this->searchQuery($search)->orderBy('id', $sort)->paginate($paginateNumber, ['*'], 'page', $page);
        // $q = request('search');

        // $workOrders = EmbellishmentWorkOrder::query()
        //     ->whereHas('buyer', function ($query) use ($q) {
        //         return $query->where('name', 'LIKE', $q);
        //     })
        //     ->orWhereHas('factory', function ($query) use ($q) {
        //         return $query->where('factory_name', 'LIKE', $q);
        //     })
        //     ->orWhereHas('supplier', function ($query) use ($q) {
        //         return $query->where('name', 'LIKE', $q);
        //     })
        //     ->orWhereHas('bookingDetails', function ($query) use ($q) {
        //         return $query->where('budget_unique_id', 'LIKE', "%$q%")
        //             ->orWhere('style', 'LIKE', "%$q%");
        //     })
        //     ->with('buyer:id,name', 'factory:id,group_name,factory_name', 'supplier:id,name');
            
    }

    private function searchQuery($search)
    { 
        if($search){
            $q = $search ;
            return  PriceQuotation::query()
            ->userWiseBuyerFilter()
            ->factoryWiseFilter()
            ->with([
                'attachments',
                'quotationInquiry',
                'buyer',
                'productDepartment',
                'season',
                'currency',
                'factory:id,factory_short_name,factory_name',
                'createdBy:id,screen_name',
            ])->filter($q);
        }
        return  PriceQuotation::query()
        ->userWiseBuyerFilter()
        ->factoryWiseFilter()
        ->with([
            'attachments',
            'quotationInquiry',
            'buyer',
            'productDepartment',
            'season',
            'currency',
            'factory:id,factory_short_name,factory_name',
            'createdBy:id,screen_name',
        ]);
        
    
            
    }
}
