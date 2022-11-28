<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\FabricConsApprovalRule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\PlyMaxCountRule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\POQtyValidationRule;
use SkylarkSoft\GoRMG\Cuttingdroplets\Rules\ProperPoBreakDown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Lot;

class BundleCardRequest extends FormRequest
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
            'required' => "This field is required"
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $lotsData = Lot::query()->whereIn('id', request()->get('lot_id'))->get();
        return [
            'size_suffix_sl_status' => 'required',
            'max_quantity' => 'required|numeric',
            'booking_consumption' => 'required|numeric',
            'booking_dia' => 'required|numeric',
            'buyer_id' => 'required',
            'order_id' => ['required', new FabricConsApprovalRule],
            'garments_item_id' => 'required',
            'purchase_order_id.*' => 'required',
            'quantity.*' => ['required', new ProperPoBreakDown($lotsData)],
            'cutting_table_id' => 'required',
            'part_id' => 'required',
            'type_id' => 'required',
            'lot_id.*' => 'required',
            'from.*' => 'required',
            'to.*' => 'required',
            'size_id.*' => 'required',
            'ply.*' => ['required', new PlyMaxCountRule()],
            'weight.*' => 'required',
            'dia.*' => 'required',
            'ratio.*' => 'required',
            'cutting_floor_id' => 'required'
        ];
    }
}
