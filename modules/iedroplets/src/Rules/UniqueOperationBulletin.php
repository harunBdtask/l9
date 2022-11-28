<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Iedroplets\Models\OperationBulletin;

class UniqueOperationBulletin implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $value = strtoupper($value);

        $orderId = request('order_id');

        $operationBulletin = OperationBulletin::where('order_id', $orderId)
            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $operationBulletin = $operationBulletin->where('id', '!=', request()->route('id'));
        }

        $operationBulletin = $operationBulletin->first();

        return !$operationBulletin;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This operation bulletin already exists for this order.';
    }
}
