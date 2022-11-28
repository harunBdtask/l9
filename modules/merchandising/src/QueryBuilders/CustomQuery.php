<?php

namespace SkylarkSoft\GoRMG\Merchandising\QueryBuilders;

use App\Constants\ApplicationConstant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomQuery extends Builder
{
    public function userWiseFilter(): CustomQuery
    {
        if ($this->isAdmin()) {
            return $this;
        }
        return $this->where('created_by', Auth::id());
    }

    public function userWiseFactories(): CustomQuery
    {
        if ($this->isAdmin()) {
            return $this;
        }
        return $this->where('id', Auth::user()->factory_id);
    }

    public function factoryWiseFilter(): CustomQuery
    {
        if ($this->isAdmin()) {
            return $this;
        }
        $associateFactories = associateFactories();
        $associateFactories[] = factoryId();
        return $this->whereIn('factory_id', $associateFactories);
    }

    public function factoryFilter(): CustomQuery
    {
        return $this->where('factory_id', Auth::user()->factory_id);
    }


    public function userWiseBuyerFilter($key = 'buyer_id'): CustomQuery
    {
        if ($this->isAdmin()) {
            return $this;
        }
        return $this->whereIn($key, $this->userWiseBuyer());
    }

    private function userWiseBuyer(): Collection
    {
        $buyerPermission = Session::get('buyerPermission');

        $viewBuyerPermission = Session::get('viewBuyerPermission');

        return collect($buyerPermission)->merge($viewBuyerPermission)->unique()->values();
    }

    public function permittedBuyer($column = 'id'): CustomQuery
    {
        return $this->whereIn($column, $this->userWiseBuyer());
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

    public function filterWithAssociateFactory($relation, $factoryId, $column = 'factory_id')
    {
        return $this->where($column, $factoryId)
            ->orWhereHas($relation, function ($query) use ($column, $factoryId) {
                $query->where($column, $factoryId);
            });
    }
}
