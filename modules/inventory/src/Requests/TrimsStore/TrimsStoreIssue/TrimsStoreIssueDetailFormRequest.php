<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreIssue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsStore\IssueQtyRule;

class TrimsStoreIssueDetailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'store_id' => 'required',
            'item_id' => 'required',
            'issue_qty' => ['required', new IssueQtyRule()],
            'uom_id' => 'required',
            'floor_id' => 'required',
            'room_id' => 'required',
            'rack_id' => 'required',
            'shelf_id' => 'required',
            'bin_id' => 'required',
        ];
    }
}
