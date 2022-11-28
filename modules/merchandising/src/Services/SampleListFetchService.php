<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use Illuminate\Support\Collection;

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
        $q = $this->q;
        $sort = ($q && is_array($q) && count($q) && \array_key_exists('sort', $q)) ? $q['sort'] : 'desc';
        $year = ($q && is_array($q) && count($q) && \array_key_exists('year', $q)) ? $q['year'] : null;
        $requisition_id = ($q && is_array($q) && count($q) && \array_key_exists('requisition_id', $q)) ? $q['requisition_id'] : null;
        $buyer_id = ($q && is_array($q) && count($q) && \array_key_exists('buyer_id', $q)) ? $q['buyer_id'] : null;
        $style_name = ($q && is_array($q) && count($q) && \array_key_exists('style_name', $q)) ? $q['style_name'] : null;
        $product_department_id = ($q && is_array($q) && count($q) && \array_key_exists('product_department_id', $q)) ? $q['product_department_id'] : null;
        $dealing_merchant_id = ($q && is_array($q) && count($q) && \array_key_exists('dealing_merchant_id', $q)) ? $q['dealing_merchant_id'] : null;
        $sample_stage = ($q && is_array($q) && count($q) && \array_key_exists('sample_stage', $q)) ? $q['sample_stage'] : null;
        return SampleRequisition::query()
            ->with([
                'factory:id,factory_name',
                'buyer:id,name',
                'department:id,product_department',
                'merchant'
            ])
            ->when($year && !$requisition_id, function ($query) use ($year) {
                $query->whereYear('req_date', $year);
            })
            ->when(($requisition_id), function ($query) use ($requisition_id) {
                $query->where('requisition_id', $requisition_id);
            })
            ->when($buyer_id && !$requisition_id, function ($query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($style_name && !$requisition_id, function ($query) use ($style_name) {
                $query->where('style_name', $style_name);
            })
            ->when($product_department_id && !$requisition_id, function ($query) use ($product_department_id) {
                $query->where('product_department_id', $product_department_id);
            })
            ->when($dealing_merchant_id && !$requisition_id, function ($query) use ($dealing_merchant_id) {
                $query->where('dealing_merchant_id', $dealing_merchant_id);
            })
            ->when($sample_stage && !$requisition_id, function ($query) use ($sample_stage) {
                $query->where('sample_stage', $sample_stage);
            })
            ->orderBy('id', $sort);
    }
}
