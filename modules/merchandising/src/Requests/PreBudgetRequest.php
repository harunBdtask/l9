<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreBudgetRequest extends FormRequest
{
    const PRE_BUDGET = 'pre-budget';
    const COMMERCIAL_COST = 'commercial-cost';
    const FABRIC_COST = 'fabric-cost';
    const KNITTING_DYEING_COST = 'knitting-dyeing-cost';
    const TRIMS_AND_ACCESSORIES_COST = 'trims-cost';
    const OTHERS_COST = 'others-cost';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $requestFor = $this->header('requestFor');

        if ($requestFor === self::PRE_BUDGET) {
            return $this->preBudgetRules();
        }

        if ($requestFor === self::COMMERCIAL_COST) {
            return $this->commercialCostRules();
        }

        if ($requestFor === self::FABRIC_COST) {
            return $this->fabricCostRules();
        }

        if ($requestFor === self::KNITTING_DYEING_COST) {
            return $this->knittingDyeingCostRules();
        }

        if ($requestFor === self::TRIMS_AND_ACCESSORIES_COST) {
            return $this->trimsAndAccessoriesCostRules();
        }

        if ($requestFor === self::OTHERS_COST) {
            return $this->otherCostRules();
        }

        return [];
    }

    public function messages()
    {
        return [
            'buyer_id.required' => 'Buyer is required',
            'agent_id.required' => 'Required',
            'order_no.*.required' => 'Required',
            'style.*.required' => 'Required',
            'image.*.mimes' => 'Must be image',
            'quantity.*.required' => 'Required',
            'quantity.*.numeric' => 'Should be number',
            'description.*.required' => 'Required',
            'unit_price.*.required' => 'Required',
            'unit_price.*.numeric' => 'Should be number',
            'cm.*.required' => 'Required',
            'cm.*.numeric' => 'Should be number',
            'percentage.*.required' => 'Required',
            'cm_total.*.required' => 'Required',
            'payment_mode.*.required' => 'Required',
            'shipment_mode.*.required' => 'Required',
            'supplier_name.*.required' => 'Required',
            'fabric_composition_id.*.required' => 'Required',
            'item_id.*.required' => 'Required',
            'name.*.required' => 'Required',
        ];
    }

    private function preBudgetRules(): array
    {
        return [
            'job_number' => ['required', Rule::unique('pre_budgets')->whereNull('deleted_at')->ignore('id')],
            'buyer_id' => 'required',
            'order_no.*' => 'required',
            'image.*' => 'mimes:jpeg,bmp,png',
            'style.*' => 'required',
            'quantity.*' => 'required|numeric',
            'description.*' => 'required',
            'unit_price.*' => 'required|numeric',
            'cm.*' => 'required|numeric',
        ];
    }

    private function commercialCostRules(): array
    {
        return [
            'percentage.*' => 'required',
            'cm_total.*' => 'required',
            'remarks.*' => 'nullable',
        ];
    }

    private function fabricCostRules(): array
    {
        return [
            'fabric_composition_id.*' => 'required',
            'quantity.*' => 'required',
            'unit_price.*' => 'required',
            'shipment_mode.*' => 'required',
            'total.*' => 'required',
            'payment_mode.*' => 'required',
            'supplier_name.*' => 'required',
            'remarks.*' => 'nullable',
        ];
    }

    private function knittingDyeingCostRules(): array
    {
        return array_merge(
            ['type.*' => [Rule::in(['Knitting', 'Dyeing'])]],
            $this->fabricCostRules()
        );
    }

    private function trimsAndAccessoriesCostRules(): array
    {
        return [
            'item_id.*' => 'required',
            'quantity.*' => 'required',
            'unit_price.*' => 'required',
            'origin.*' => 'required',
            'shipment_mode.*' => 'required',
            'total.*' => 'required',
            'payment_mode.*' => 'required',
            'supplier_name.*' => 'required',
            'remarks.*' => 'nullable',
        ];
    }

    private function otherCostRules(): array
    {
        return [
            'name.*' => 'required',
            'quantity.*' => 'required',
            'unit_price.*' => 'required',
            'origin.*' => 'required',
            'shipment_mode.*' => 'required',
            'total.*' => 'required',
            'payment_mode.*' => 'required',
            'supplier_name.*' => 'required',
            'remarks.*' => 'nullable',
        ];
    }
}
