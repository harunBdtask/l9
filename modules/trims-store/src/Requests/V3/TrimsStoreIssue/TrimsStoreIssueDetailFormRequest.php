<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreIssue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\TrimsStore\Rules\V3\QtyRule;
use SkylarkSoft\GoRMG\TrimsStore\Rules\V3\TrimsStoreIssueDetail\IssueQtyRule;

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
            'buyer_id' => 'required',
            'style_id' => 'required',
            'garments_item_id' => 'required',
            'item_id' => 'required',
            'issue_qty' => ['required', new QtyRule(), new IssueQtyRule()],
            'uom_id' => 'required',
            'rate' => 'required',
            'amount' => 'required',
            'floor_id' => 'required',
            'room_id' => 'required',
            'rack_id' => 'required',
            'shelf_id' => 'required',
            'bin_id' => 'required',
        ];
    }
}
