<?php

namespace SkylarkSoft\GoRMG\Sample\Services;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Sample\Models\SampleTNA;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SampleTNAListFetchService
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
        if (! empty($q)) {
            $buyer = Buyer::where('name', $q)->first()->id ?? null;
            $factory = Factory::where('factory_name', $q)->first()->id ?? null;
        }

        return SampleTNA::query()
            ->with([
                'factory:id,factory_name',
                'buyer:id,name',
            ])
            ->when($buyer && ! $factory, function ($query) use ($buyer) {
                $query->where('buyer_id', $buyer);
            })
            ->when($factory && ! $buyer, function ($query) use ($factory) {
                $query->where('factory_id', $factory);
            })
            ->when($q && ! $buyer && ! $factory, function ($query) use ($q) {
                $query->orWhere('unique_id', $q);
                $query->orWhere('requisition_id', $q);
                $query->orWhere('style_name', $q);
                $query->orWhere('booking_no', $q);
                $query->orWhere('control_ref_no', $q);
                $query->orWhere('total_lead_time', $q);
            })
            ->orderBy('id', 'desc');
    }
}
