<?php

namespace SkylarkSoft\GoRMG\Sample\Services;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Sample\Models\SampleTrimsReceive;

class SampleTrimsReceiveFetchService
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

        return SampleTrimsReceive::query()
            ->with([
                'factory:id,factory_name',
            ])
            ->when($q, function ($query) use ($q) {
                $query->orWhere('unique_id', $q);
                $query->orWhere('issue_challan_no', $q);
            })
            ->orderBy('id', 'desc');
    }
}
