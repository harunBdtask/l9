<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests\TrimsStore\TrimsStoreIssue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TrimsStoreIssueFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'booking_no' => 'required',
            'trims_store_bin_card_id' => 'required',
            'store_id' => 'required',
            'challan_no' => 'required',
        ];
    }
}
