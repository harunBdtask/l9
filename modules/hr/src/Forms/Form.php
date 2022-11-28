<?php

namespace SkylarkSoft\GoRMG\HR\Forms;


use Illuminate\Foundation\Http\FormRequest;

abstract class Form extends FormRequest
{
    abstract function handle();

    public function authorize()
    {
        return true;
    }
}
