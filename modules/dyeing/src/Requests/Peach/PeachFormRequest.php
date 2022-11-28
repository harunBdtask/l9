<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests\Peach;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PeachFormRequest extends FormRequest
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
            'entry_basis' => 'required',
            'dyeing_batch_id' => 'required_if:entry_basis,1',
            'dyeing_batch_no' => 'required_if:entry_basis,1',
            'textile_order_id' => 'required_if:entry_basis,2',
            'textile_order_no' => 'required_if:entry_basis,2',
            'production_date' => 'required',
            'sub_dyeing_unit_id' => 'required',
            'shift_id' => 'required',
            'dyeing_machine_id' => 'required',
            'peach_details.*.no_of_roll' => 'required',
            'peach_details.*.finish_qty' => 'required',
            'peach_details.*.unit_cost' => 'required',
        ];
    }

}
