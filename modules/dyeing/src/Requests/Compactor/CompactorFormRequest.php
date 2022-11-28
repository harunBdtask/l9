<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests\Compactor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CompactorFormRequest extends FormRequest
{

    public function authorize():bool
    {
        return Auth::check();
    }

    public function rules():array
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'entry_basis' => 'required',
            'dyeing_batch_id' => 'required_if:entry_basis,1',
            'textile_order_id' => 'required_if:entry_basis,2',
            'production_date' => 'required',
            'compactor_details' => 'required',
            'compactor_details.*.fin_no_of_roll' => 'numeric|gte:0',
            'compactor_details.*.finish_qty' => 'numeric|gte:0',
            'compactor_details.*.unit_cost' => 'numeric|gte:0',
        ];
    }

}
