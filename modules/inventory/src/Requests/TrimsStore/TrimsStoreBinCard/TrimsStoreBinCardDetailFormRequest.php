<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreBinCard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Inventory\Rules\TrimsStore\BinCardIssueQtyRule;

class TrimsStoreBinCardDetailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'trims_store_mrr_id' => 'required',
            'factory_id' => 'required',
            'store_id' => 'required',
            'bin_card_date' => 'required',
            'item_id' => 'required',
            'booking_qty' => 'required',
            'uom_id' => 'required',
            'floor_id' => 'required',
            'room_id' => 'required',
            'rack_id' => 'required',
            'shelf_id' => 'required',
            'bin_id' => 'required',
//            'issue_qty' => ['required', new BinCardIssueQtyRule()],
//            'issue_date' => 'required',
        ];
    }
}
