<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Party;

class UniqueParty implements Rule
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

        $party = Party::where([
            'party_name' => $value,
            'party_type_id' => request()->get('party_type_id'),
        ]);

        if (request()->route('id')) {
            $party = $party->where('id', '!=', request()->route('id'));
        }

        $party = $party->first();

        return $party ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This party already exits.';
    }
}
