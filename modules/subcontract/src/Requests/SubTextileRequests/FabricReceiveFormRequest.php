<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FabricReceiveFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $validate = [
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'receive_basis' => 'required',
            'challan_date' => 'required',
            'sub_grey_store_id' => 'required',
        ];
        if (empty($this->route('subGreyStoreReceive'))) {
            $validate['challan_no'] = [
                'required',
                'alpha_dash',
                Rule::unique('sub_grey_store_receives', 'challan_no')
                    ->ignore($this->route('subGreyStoreReceive'))
                    ->whereNull('deleted_at'),
            ];
        }

        return $validate;
    }
}
