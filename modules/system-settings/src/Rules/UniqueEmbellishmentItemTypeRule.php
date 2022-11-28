<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;

class UniqueEmbellishmentItemTypeRule implements Rule
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
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $type = EmbellishmentItem::where([
      'name' => request()->name,
      'type' => $value,
    ]);

        if (request()->route('id')) {
            $type = $type->where('id', '!=', request()->route('id'));
        }

        $type = $type->first();

        return $type ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This type already exits for the given embellishment.';
    }
}
