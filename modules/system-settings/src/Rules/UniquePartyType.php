<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\PartyType;

class UniquePartyType implements Rule
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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $party_type = PartyType::where([
            'party_type' => $value,
        ]);

        if (request()->route('id')) {
            $party_type = $party_type->where('id', '!=', request()->route('id'));
        }

        $party_type = $party_type->first();

        return $party_type ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This party type already exits.';
    }
}
