<?php

namespace SkylarkSoft\GoRMG\Commercial\Forms;

use Illuminate\Foundation\Http\FormRequest;

abstract class Form extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    abstract public function persist();
}
