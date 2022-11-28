<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Filters;

class BookingFilters
{
    private $supplier_id;

    public function __construct()
    {
        $this->supplier_id = request('supplier_id');
    }

    public function filterFabricSourceWise($fabric): bool
    {
        if (! request('fabric_source')) {
            return false;
        }

        if ($fabricSource = request('fabric_source')) {
            return $fabricSource == $fabric['fabric_source'];
        }

        return true;
    }

    public function filterSupplierWise($hasMatchingSupplier): \Closure
    {
        return function ($fabric) use ($hasMatchingSupplier) {
            if ($hasMatchingSupplier) {
                return $fabric['supplier_id'] == request('supplier_id');
            }

            return ! $fabric['supplier_id'];
        };
    }
}
