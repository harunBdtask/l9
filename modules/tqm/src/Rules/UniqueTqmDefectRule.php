<?php

namespace SkylarkSoft\GoRMG\TQM\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\TQM\Models\TqmDefect;

class UniqueTqmDefectRule implements Rule
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

        $exists = TqmDefect::query()
            ->where([
                'factory_id' => request()->get('factory_id'),
                'section' => request()->get('section'),
                'name' => $value,
            ]);

        if (request()->route('defect') && request()->route('defect')->id) {
            $exists = $exists->where('id', '!=', request()->route('defect')->id);
        }

        $exists = $exists->first();

        return $exists ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This name for defect already exits.';
    }
}
