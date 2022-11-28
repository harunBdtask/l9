<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ColorSizeWiseProductionRules\EmbellishmentIssueQtyRule as ColorSizeWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ColorWiseProductionRules\EmbellishmentIssueQtyRule as ColorWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\OrderWiseProductionRules\EmbellishmentIssueQtyRule as OrderWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ManualProductionDateRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ManualProductionQtyRule;

class ManualEmbellishmentIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages()
    {
        return [
            'required' => 'Required field',
            'integer' => 'Integer field',
            'min' => 'Negative value not allowed',
        ];
    }

    public function rules(): array
    {
        $color_id = request()->get('color_id') ?? null;
        $size_id = request()->get('size_id') ?? null;
        $production_qty = request()->get('production_qty') ?? null;
        $subcontract_factory_id = request()->get('subcontract_factory_id') ?? null;
        $rules = [
            'production_date' => ['required', 'date', new ManualProductionDateRule()],
            'embl_name' => 'required|integer',
            'embl_type' => 'nullable',
            'source' => 'required|integer',
            'factory_id' => 'required|integer',
            'buyer_id' => 'required|integer',
            'order_id' => 'required|integer',
            'garments_item_id' => 'required|integer',
            'purchase_order_id' => 'required|integer',
            'remarks' => 'nullable',
        ];
        if ($subcontract_factory_id) {
            $rules['subcontract_factory_id'] = ['required', 'integer'];
            $rules['sub_embl_floor_id'] = ['required', 'integer'];
        }
        if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
            // for size wise data rule
            $rules['color_id'] = ['required', 'integer'];
            $rules['size_id'] = ['required', 'array'];
            $rules['no_of_bags'] = ['required', 'array'];
            $rules['challan_no'] = ['required', 'array'];
            $rules['production_qty'] = ['required', 'array'];
            foreach ($production_qty as $p_key => $val) {
                if (!$val || $val <= 0) {
                    $rules['challan_no.' . $p_key] = ['nullable'];
                } else {
                    $rules['challan_no.' . $p_key] = ['required'];
                }
            }
            $rules['size_id.*'] = ['required', 'integer'];
            $rules['no_of_bags.*'] = ['nullable', 'integer'];
            $rules['production_qty.*'] = ['nullable', 'integer', 'min:0', new ManualProductionQtyRule(), new ColorSizeWiseProductionQtyRule()];
            $rules['remaining_production_qty.*'] = ['required', 'integer', 'min:0'];
        } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
            // for color wise data rule
            $rules['color_id'] = ['required', 'array'];
            $rules['no_of_bags'] = ['required', 'array'];
            $rules['challan_no'] = ['required', 'array'];
            $rules['production_qty'] = ['required', 'array'];
            $rules['color_id.*'] = ['required', 'integer'];
            $rules['no_of_bags.*'] = ['nullable', 'integer'];
            $rules['production_qty.*'] = ['nullable', 'integer', 'min:0', new ManualProductionQtyRule(), new ColorWiseProductionQtyRule()];
            foreach ($production_qty as $p_key => $val) {
                if (!$val || $val <= 0) {
                    $rules['challan_no.' . $p_key] = ['nullable'];
                } else {
                    $rules['challan_no.' . $p_key] = ['required'];
                }
            }
            $rules['challan_no.*'] = ['nullable'];
            $rules['remaining_production_qty.*'] = ['required', 'integer', 'min:0'];
        } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
            // for order wise data rule
            $rules['no_of_bags'] = ['required', 'integer'];
            $rules['production_qty'] = ['required', 'integer', 'min:0', new ManualProductionQtyRule(), new OrderWiseProductionQtyRule()];
            $rules['remaining_production_qty'] = ['required', 'integer', 'min:0'];
            $rules['challan_no'] = ['required'];
        } else {
            // for indistinctive data rule
            $rules['color_id'] = ['required', 'integer'];
            $rules['no_of_bags'] = ['required', 'integer'];
            $rules['production_qty'] = ['required', 'integer', 'min:0', new ManualProductionQtyRule()];
            $rules['remaining_production_qty'] = ['required', 'integer', 'min:0'];
            $rules['challan_no'] = ['required'];
        }
        return $rules;
    }
}
