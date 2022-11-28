<?php

namespace SkylarkSoft\GoRMG\Planing\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Planing\Models\ContainerProfile\ContainerProfileDetail;

class ContainerNameRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $explode = explode('.', $attribute);
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        $id = request()->input('details')[$explode[1]]['id'] ?? null;

        $containerProfileDetail = ContainerProfileDetail::query()
            ->where('container_no', $value)
            ->when($id, function (Builder $query) use ($id) {
                $query->where('id', '<>', $id);
            })
            ->where(function (Builder $query) use ($startDate, $endDate) {
                $query->when($startDate && $endDate, function (Builder $query) use ($startDate, $endDate) {
                    $query->whereHas('containerProfile', function (Builder $query) use ($startDate) {
                        $query->whereDate('start_date', '<=', $startDate)
                            ->whereDate('end_date', '>=', $startDate);
                    })->orWhereHas('containerProfile', function (Builder $query) use ($endDate) {
                        $query->whereDate('start_date', '<=', $endDate)
                            ->whereDate('end_date', '>=', $endDate);
                    });
                });
            })
            ->first();

        if (isset($containerProfileDetail)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This container already use';
    }
}
