<?php

namespace SkylarkSoft\GoRMG\Sample\Services;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Sample\Models\SampleTrimsIssue;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class SampleTrimsIssueFetchService
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
        if (! empty($q)) {
            $buyer = Buyer::where('name', $q)->first()->id ?? null;
        }

        return SampleTrimsIssue::query()
            ->with([
                'factory:id,factory_name',
                'buyer:id,name',
            ])
            ->when($buyer, function ($query) use ($buyer) {
                $query->where('buyer_id', $buyer);
            })
            ->when($q && ! $buyer, function ($query) use ($q) {
                $query->orWhere('unique_id', $q);
                $query->orWhere('issue_challan_no', $q);
                $query->orWhere('style_name', $q);
            })
            ->orderBy('id', 'desc');
    }
}
