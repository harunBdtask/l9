<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;

class FileSize implements Rule
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
        Log::info($attribute);
        $size = $value ? FileUploadRemoveService::getBase64FileSize($value) : 0;
//        if ($size < 60)  {
//            return true;
//        }
//        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Max Size Exceed';
    }
}
