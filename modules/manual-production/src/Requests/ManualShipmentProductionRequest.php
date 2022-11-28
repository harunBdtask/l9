<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ColorSizeWiseProductionRules\ShipmentQtyRule as ColorSizeWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ColorWiseProductionRules\ShipmentQtyRule as ColorWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\OrderWiseProductionRules\ShipmentQtyRule as OrderWiseProductionQtyRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ManualProductionDateRule;
use SkylarkSoft\GoRMG\ManualProduction\Rules\ManualProductionQtyRule;

class ManualShipmentProductionRequest extends FormRequest
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
        $rules = [
            'production_date' => ['required', 'date', new ManualProductionDateRule()],
            'factory_id' => 'required|integer',
            'buyer_id' => 'required|integer',
            'order_id' => 'required|integer',
            'garments_item_id' => 'required|integer',
            'purchase_order_id' => 'required|integer',
            'responsible_person' => 'nullable',
            'agent' => 'nullable',
            'destination' => 'nullable',
            'vehicle_no' => 'nullable',
            'driver' => 'nullable',
            'remarks' => 'nullable',
        ];
        if ($size_id && is_array($size_id) && count($size_id) && $production_qty && is_array($production_qty)) {
            // for size wise data rule
            $rules['color_id'] = ['required', 'integer'];
            $rules['size_id'] = ['required', 'array'];
            $rules['production_qty'] = ['required', 'array'];
            $rules['remaining_production_qty'] = ['required', 'array'];
            $rules['short_qty'] = ['required', 'array'];
            $rules['carton_qty'] = ['required', 'array'];
            $rules['status'] = ['required', 'array'];
            $rules['size_id.*'] = ['required', 'integer'];
            $rules['production_qty.*'] = ['nullable', 'integer', 'min:0', new ManualProductionQtyRule(), new ColorSizeWiseProductionQtyRule()];
            $rules['remaining_production_qty.*'] = ['required', 'integer', 'min:0'];
            $rules['short_qty.*'] = ['nullable', 'integer', 'min:0'];
            $rules['carton_qty.*'] = ['nullable', 'integer', 'min:0'];
            $rules['status.*'] = ['nullable', 'integer'];
        } elseif ($color_id && is_array($color_id) && count($color_id) && $production_qty && is_array($production_qty)) {
            // for color wise data rule
            $rules['color_id'] = ['required', 'array'];
            $rules['production_qty'] = ['required', 'array'];
            $rules['remaining_production_qty'] = ['required', 'array'];
            $rules['short_qty'] = ['required', 'array'];
            $rules['carton_qty'] = ['required', 'array'];
            $rules['status'] = ['required', 'array'];
            $rules['color_id.*'] = ['required', 'integer'];
            $rules['production_qty.*'] = ['nullable', 'integer', 'min:0', new ManualProductionQtyRule(), new ColorWiseProductionQtyRule()];
            $rules['remaining_production_qty.*'] = ['required', 'integer', 'min:0'];
            $rules['short_qty.*'] = ['nullable', 'integer', 'min:0'];
            $rules['carton_qty.*'] = ['nullable', 'integer', 'min:0'];
            $rules['status.*'] = ['nullable', 'integer'];
        } elseif (!$color_id && !$size_id && $production_qty && !is_array($production_qty)) {
            // for order wise data rule
            $rules['short_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['carton_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['status'] = ['nullable', 'integer', 'min:0'];
            $rules['production_qty'] = ['required', 'integer', 'min:0', new ManualProductionQtyRule(), new OrderWiseProductionQtyRule()];
            $rules['remaining_production_qty'] = ['required', 'integer'];
        } else {
            // for indistinctive data rule
            $rules['short_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['carton_qty'] = ['nullable', 'integer', 'min:0'];
            $rules['status'] = ['nullable', 'integer'];
            $rules['production_qty'] = ['required', 'integer', 'min:0', new ManualProductionQtyRule()];
            $rules['remaining_production_qty'] = ['required', 'integer', 'min:0'];
        }
        return $rules;
    }
}
