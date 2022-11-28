<?php

namespace SkylarkSoft\GoRMG\Approval\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;

class PriorityUniqueRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $requestedData = request()->all();
        $index = explode('.', $attribute)[0] ?? null;

        $approval = Approval::query()
            ->where('priority', $requestedData[$index]['priority'])
            ->where('page_name', $requestedData[$index]['page_name'])
            ->where('id', '!=', $requestedData[$index]['id'])
            ->exists();

        if ($approval) {
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
        return 'Priority already exists.';
    }
}
