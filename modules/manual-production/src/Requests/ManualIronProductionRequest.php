<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ColorSizeWiseProductionRules\FinishingIronQtyRule as ColorSizeWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ColorWiseProductionRules\FinishingIronQtyRule as ColorWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\OrderWiseProductionRules\FinishingIronQtyRule as OrderWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ManualProductionDateRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ManualProductionQtyRule;

class ManualIronProductionRequest extends FormRequest
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
        $subcontract_factory_id = request()->get('subcontract_factory_id') ?? null;
        $production_qty = request()->get('production_qty') ?? null;
        $rules = [
            'production_date' => ['required', 'date', new ManualProductionDateRule()],
            'source' => 'required|integer',
            'factory_id' => 'required|integer',
            'subcontract_factory_id' => 'nullable|integer',
            'buyer_id' => 'required|integer',
            'order_id' => 'required|integer',
            'garments_item_id' => 'required|integer',
            'purchase_order_id' => 'required|integer',
            'produced_by' => 'required|integer',
            'reporting_hour' => 'required|integer',
            'supervisor' => 'nullable',
            'remarks' => 'nullable',
        ];
        if ($subcontract_factory_id) {
            $rules['subcontract_factory_id'] = ['required', 'integer'];
            $rules['sub_finishing_floor_id'] = ['required', 'integer'];
            $rules['sub_finishing_table_id'] = ['required', 'integer'];
        } else {
            $rules['finishing_floor_id'] = ['nullable', 'integer'];
            $rules['finishing_table_id'] = ['nullable', 'integer'];
        }
        if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
            // for size wise data rule
            $rules['color_id'] = ['required', 'integer'];
            $rules['size_id'] = ['required', 'array'];
            $rules['production_qty'] = ['required', 'array'];
            $rules['remaining_production_qty'] = ['required', 'array'];
            $rules['re_iron_qty'] = ['required', 'array'];
            $rules['rejection_qty'] = ['required', 'array'];
            $rules['challan_no'] = ['required', 'array'];
            $rules['size_id.*'] = ['required', 'integer'];
            $rules['production_qty.*'] = ['nullable', 'integer', 'min:0', new ManualProductionQtyRule(), new ColorSizeWiseProductionQtyRule()];
            $rules['remaining_production_qty.*'] = ['required', 'integer', 'min:0'];
            $rules['re_iron_qty.*'] = ['nullable', 'integer', 'min:0'];
            $rules['rejection_qty.*'] = ['nullable', 'integer', 'min:0'];
            $rules['challan_no.*'] = ['nullable'];
        } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
            // for color wise data rule
            $rules['color_id'] = ['required', 'array'];
            $rules['production_qty'] = ['required', 'array'];
            $rules['remaining_production_qty'] = ['required', 'array'];
            $rules['re_iron_qty'] = ['required', 'array'];
            $rules['rejection_qty'] = ['required', 'array'];
            $rules['challan_no'] = ['required', 'array'];
            $rules['color_id.*'] = ['required', 'integer'];
            $rules['production_qty.*'] = ['nullable', 'integer', 'min:0', new ManualProductionQtyRule(), new ColorWiseProductionQtyRule()];
            $rules['remaining_production_qty.*'] = ['required', 'integer', 'min:0'];
            $rules['re_iron_qty.*'] = ['nullable', 'integer', 'min:0'];
            $rules['rejection_qty.*'] = ['nullable', 'integer', 'min:0'];
            $rules['challan_no.*'] = ['nullable'];
        } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
            // for order wise data rule
            $rules['re_iron_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['rejection_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['challan_no'] = ['required'];
            $rules['production_qty'] = ['required', 'integer', 'min:0', new ManualProductionQtyRule(), new OrderWiseProductionQtyRule()];
            $rules['remaining_production_qty'] = ['required', 'integer', 'min:0'];
        } else {
            // for indistinctive data rule
            $rules['re_iron_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['rejection_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['challan_no'] = ['required'];
            $rules['production_qty'] = ['required', 'integer', 'min:0', new ManualProductionQtyRule()];
            $rules['remaining_production_qty'] = ['required', 'integer', 'min:0'];
        }
        return $rules;
    }
}
