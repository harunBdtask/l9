<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\UniqueColorOfOrder;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\CheckAvailableStoreAmount;

class CuttingRequisitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

      /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return bool|array
     */
    public function messages()
    {
        return [            
            'buyer_id.*.required' => 'Buyer selection is required.',
            'order_id.*.required' => 'Booking no selection is required.',
            'fabric_type.*.required' => 'Fabric type is required.',
            'color_id.*.required' => 'Color selection is required.',
            'garments_part_id.*.required' => 'Garments part required',
            'batch_no.*.required' => 'Batch no required',
            'requisition_amount.*.required' => 'Requisition amount is required.',
            'requisition_amount.*.numeric' => 'Requisition amount must be numeric.'         
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cutting_requisition_no' => 'required',
            'row_count.*' => 'required',
            'buyer_id.*' => 'required',
            'order_id.*' => 'required',
            'composition_fabric_id.*' => 'required',
            'fabric_type.*' => 'required',
            'color_id.*' => ['required', new UniqueColorOfOrder],
            'garments_part_id.*' => 'required',
            'batch_no.*' => 'required',
            'requisition_amount.*' => [
                //'required',
                'numeric',
                'min:1',
                new CheckAvailableStoreAmount
            ]
        ];
    }
}
