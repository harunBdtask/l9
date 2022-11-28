<?php

namespace SkylarkSoft\GoRMG\Sample\Services;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;

class SampleListFetchService
{
    protected $q;

    public function __construct($q)
    {
        $this->q = $q ?? null;
    }

    public function get($paginateNumber)
    {
        return $this->searchQuery()->paginate($paginateNumber);
    }

    public function getAll(): Collection
    {
        return $this->searchQuery()->get();
    }

    public function searchQuery()
    {
        $q = $this->q ?? null;
        $buyer = null;
        $factory = null;
        $productDepartment = null;
        $gmtsItem = null;
        $sampleStage = null;
        if (! empty($q)) {
            $buyer = Buyer::where('name', $q)->first()->id ?? null;
            $factory = Factory::where('factory_name', $q)->first()->id ?? null;
            $productDepartment = ProductDepartments::where('product_department', $q)->first()->id ?? null;
            $gmtsItem = GarmentsItem::where('name', $q)->first()->id ?? null;
            if ($q == 'After Order') {
                $sampleStage = 'after_order';
            } elseif ($q == 'Before Order') {
                $sampleStage = 'before_order';
            }
        }

        return SampleOrderRequisition::query()
            ->with([
                'factory:id,factory_name',
                'buyer:id,name',
                'department:id,product_department',
                'merchant',
            ])
            ->when($buyer && ! $sampleStage && ! $gmtsItem && ! $productDepartment && ! $factory, function ($query) use ($buyer) {
                $query->where('buyer_id', $buyer);
            })
            ->when($factory && ! $sampleStage && ! $gmtsItem && ! $productDepartment && ! $buyer, function ($query) use ($factory) {
                $query->where('factory_id', $factory);
            })
            ->when($productDepartment && ! $sampleStage && ! $gmtsItem && ! $buyer && ! $factory, function ($query) use ($productDepartment) {
                $query->where('product_department_id', $productDepartment);
            })
            ->when($gmtsItem && ! $sampleStage && ! $buyer && ! $productDepartment && ! $factory, function ($query) use ($gmtsItem) {
                $query->whereHas('details', function ($qry) use ($gmtsItem) {
                    $qry->where('gmts_item_id', $gmtsItem);
                });
            })
            ->when($sampleStage && ! $buyer && ! $gmtsItem && ! $productDepartment && ! $factory, function ($query) use ($sampleStage) {
                $query->where('sample_stage', $sampleStage);
            })
            ->when($q && ! $buyer && ! $sampleStage && ! $gmtsItem && ! $productDepartment && ! $factory, function ($query) use ($q) {
                $query->orWhere('requisition_id', $q);
                $query->orWhere('style_name', $q);
                $query->orWhere('booking_no', $q);
                $query->orWhere('control_ref_no', $q);
            })
            ->orderBy('id', 'desc');
    }
}
