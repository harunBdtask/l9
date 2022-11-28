<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ColorSizeWiseProductionRules\SewingOutputQtyRule as ColorSizeWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ColorWiseProductionRules\SewingOutputQtyRule as ColorWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\OrderWiseProductionRules\SewingOutputQtyRule as OrderWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualHourlySewingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ManualProductionDateRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ManualProductionQtyRule;

class ManualSewingOutputProductionRequest extends FormRequest
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
        $entry_format = request()->get('entry_format') ?? null;
        $rules = [
            'production_date' => ['required', 'date', new ManualProductionDateRule()],
            'source' => 'required|integer',
            'factory_id' => 'required|integer',
            'subcontract_factory_id' => 'nullable|integer',
            'buyer_id' => 'required|integer',
            'order_id' => 'required|integer',
            'garments_item_id' => 'required|integer',
            'purchase_order_id' => 'required|integer',
            'supervisor' => 'nullable',
            'produced_by' => 'required|integer',
            'reporting_hour' => 'required|integer',
            'entry_format' => 'required|integer',
            'remarks' => 'nullable',
        ];
        if ($subcontract_factory_id) {
            $rules['subcontract_factory_id'] = ['required', 'integer'];
            $rules['sub_sewing_floor_id'] = ['required', 'integer'];
            $rules['sub_sewing_line_id'] = ['required', 'integer'];
        } else {
            $rules['floor_id'] = ['required', 'integer'];
            $rules['line_id'] = ['required', 'integer'];
        }
        if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
            // for size wise data rule
            $rules['color_id'] = ['required', 'integer'];
            $rules['size_id'] = ['required', 'array'];
            $rules['production_qty'] = ['required', 'array'];
            $rules['remaining_production_qty'] = ['required', 'array'];
            $rules['rejection_qty'] = ['required', 'array'];
            $rules['alter_qty'] = ['required', 'array'];
            $rules['challan_no'] = ['required', 'array'];
            $rules['size_id.*'] = ['required', 'integer'];
            $rules['production_qty.*'] = ['integer', 'min:0', 'nullable', new ManualProductionQtyRule(), new ColorSizeWiseProductionQtyRule()];
            $rules['rejection_qty.*'] = ['integer', 'min:0', 'nullable'];
            $rules['alter_qty.*'] = ['integer', 'min:0', 'nullable'];
            $rules['remaining_production_qty.*'] = ['required', 'integer', 'min:0'];
            foreach ($production_qty as $p_key => $val) {
                if (!$val || $val <= 0) {
                    $rules['challan_no.' . $p_key] = ['nullable'];
                } else {
                    $rules['challan_no.' . $p_key] = ['required'];
                }
            }
            if ($entry_format && $entry_format == ManualHourlySewingProduction::HOURLY_ENTRY_FORMAT) {
                $rules['hour_8'] = ['required', 'array'];
                $rules['hour_9'] = ['required', 'array'];
                $rules['hour_10'] = ['required', 'array'];
                $rules['hour_11'] = ['required', 'array'];
                $rules['hour_12'] = ['required', 'array'];
                $rules['hour_13'] = ['required', 'array'];
                $rules['hour_14'] = ['required', 'array'];
                $rules['hour_15'] = ['required', 'array'];
                $rules['hour_16'] = ['required', 'array'];
                $rules['hour_17'] = ['required', 'array'];
                $rules['hour_18'] = ['required', 'array'];
                $rules['hour_19'] = ['required', 'array'];
                $rules['hour_20'] = ['required', 'array'];
                $rules['hour_21'] = ['required', 'array'];
                $rules['hour_22'] = ['required', 'array'];
                $rules['hour_23'] = ['required', 'array'];
                $rules['hour_8.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_9.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_10.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_11.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_12.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_13.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_14.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_15.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_16.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_17.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_18.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_19.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_20.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_21.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_22.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_23.*'] = ['nullable', 'integer', 'min:0'];
            }
        } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
            // for color wise data rule
            $rules['color_id'] = ['required', 'array'];
            $rules['production_qty'] = ['required', 'array'];
            $rules['rejection_qty'] = ['required', 'array'];
            $rules['alter_qty'] = ['required', 'array'];
            $rules['remaining_production_qty'] = ['required', 'array'];
            $rules['challan_no'] = ['required', 'array'];
            $rules['color_id.*'] = ['required', 'integer'];
            $rules['production_qty.*'] = ['integer', 'min:0', 'nullable', new ManualProductionQtyRule(), new ColorWiseProductionQtyRule()];
            $rules['rejection_qty.*'] = ['integer', 'min:0', 'nullable'];
            $rules['alter_qty.*'] = ['integer', 'min:0', 'nullable'];
            $rules['remaining_production_qty.*'] = ['required', 'integer', 'min:0'];
            foreach ($production_qty as $p_key => $val) {
                if (!$val || $val <= 0) {
                    $rules['challan_no.' . $p_key] = ['nullable'];
                } else {
                    $rules['challan_no.' . $p_key] = ['required'];
                }
            }
            if ($entry_format && $entry_format == ManualHourlySewingProduction::HOURLY_ENTRY_FORMAT) {
                $rules['hour_8'] = ['required', 'array'];
                $rules['hour_9'] = ['required', 'array'];
                $rules['hour_10'] = ['required', 'array'];
                $rules['hour_11'] = ['required', 'array'];
                $rules['hour_12'] = ['required', 'array'];
                $rules['hour_13'] = ['required', 'array'];
                $rules['hour_14'] = ['required', 'array'];
                $rules['hour_15'] = ['required', 'array'];
                $rules['hour_16'] = ['required', 'array'];
                $rules['hour_17'] = ['required', 'array'];
                $rules['hour_18'] = ['required', 'array'];
                $rules['hour_19'] = ['required', 'array'];
                $rules['hour_20'] = ['required', 'array'];
                $rules['hour_21'] = ['required', 'array'];
                $rules['hour_22'] = ['required', 'array'];
                $rules['hour_23'] = ['required', 'array'];
                $rules['hour_8.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_9.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_10.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_11.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_12.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_13.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_14.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_15.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_16.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_17.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_18.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_19.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_20.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_21.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_22.*'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_23.*'] = ['nullable', 'integer', 'min:0'];
            }
        } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
            // for order wise data rule
            $rules['challan_no'] = ['required'];
            $rules['production_qty'] = ['required', 'integer', 'min:0', new ManualProductionQtyRule(), new OrderWiseProductionQtyRule()];
            $rules['rejection_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['alter_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['remaining_production_qty'] = ['required', 'integer', 'min:0'];
            if ($entry_format && $entry_format == ManualHourlySewingProduction::HOURLY_ENTRY_FORMAT) {
                $rules['hour_8'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_9'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_10'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_11'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_12'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_13'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_14'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_15'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_16'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_17'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_18'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_19'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_20'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_21'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_22'] = ['nullable', 'integer', 'min:0'];
                $rules['hour_23'] = ['nullable', 'integer', 'min:0'];
            }
        } else {
            // for indistinctive data rule
            $rules['challan_no'] = ['required'];
            $rules['production_qty'] = ['required', 'integer', 'min:0', new ManualProductionQtyRule()];
            $rules['rejection_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['alter_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['remaining_production_qty'] = ['required', 'integer', 'min:0'];
        }
        return $rules;
    }
}
