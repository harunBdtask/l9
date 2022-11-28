<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATask;

class ConnectedTaskRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $query = TNATask::query()->where('id', $value)->where('plan_date_is_editable', 1)->exists();
        if ($query) {
            return true;
        }
        return false;
    }

    public function message(): string
    {
        return 'Connected task must be an editable task';
    }
}
