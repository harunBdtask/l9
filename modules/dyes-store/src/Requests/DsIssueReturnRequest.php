<?php

namespace SkylarkSoft\GoRMG\DyesStore\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\DyesStore\Rules\DsIssueReturnQtyRule;

class DsIssueReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "issue_id" => "required",
            "return_date" => "required",
            "details.*.return_qty" => ["required", new DsIssueReturnQtyRule() ],
        ];
    }
}
