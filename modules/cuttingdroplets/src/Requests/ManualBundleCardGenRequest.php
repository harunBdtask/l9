<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\FabricConsApprovalRule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\UniqueCuttingNo;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\UnsignedInteger;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\UniqueArray;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\SequenceArray;

class ManualBundleCardGenRequest extends FormRequest
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
     * Get the validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'This field is required'
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
            'booking_consumption' => 'nullable|numeric',
            'booking_dia' => 'nullable|numeric',
            'buyer_id' => 'required',
            'order_id' => ['required', new FabricConsApprovalRule],
            'garments_item_id' => 'required',
            'cutting_no.*' => 'required',
            'cutting_floor_id' => 'required',
            'cutting_table_id' => 'required',
            'part_id' => 'required',
            'type_id' => 'required',
            'bundle_no' => ['required', new SequenceArray],
            'bundle_no.*' => ['required', new UnsignedInteger],
            'lot.*' => 'required',
            'size.*' => 'required',
            'roll_no.*' => 'required|numeric|integer|min:1',
            'quantity.*' => ['required', new UnsignedInteger],
            'sl_start.*' => ['required', new UnsignedInteger],
            'sl_end.*' => ['required', new UnsignedInteger],
        ];

    }
}
