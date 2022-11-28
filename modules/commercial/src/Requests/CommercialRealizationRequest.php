<?php

namespace SkylarkSoft\GoRMG\Commercial\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Commercial\Rules\CommercialRealizedValueRule;

class CommercialRealizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function messages(): array
    {
        return [
            'required' => 'Required',
            'integer' => 'Must be integer',
            'numeric' => 'Must be number',
            'array' => 'Invalida data',
            'min' => 'Positive number required'
        ];
    }

    public function rules(): array
    {
        $requiredDate = 'required|date';
        $requiredInteger = 'required|integer';
        $requiredArray = 'required|array';
        $nullableArray = 'nullable|array';
        $nullableInteger = 'nullable|integer';
        $requiredNumeric = 'required|numeric';
        $requiredNumericMinZero = 'required|numeric|min:0';
        return [
            'realization_date' => $requiredDate,
            'document_submission_id' => $requiredInteger,
            'dbp_type' => $requiredInteger,
            'bank_ref_bill' => 'required',
            'buyer_id' => $requiredInteger,
            'factory_id' => $requiredInteger,
            'document_submission_invoice_id' => $requiredArray,
            'export_invoice_id' => $requiredArray,
            'sales_contract_id' => $nullableArray,
            'export_lc_id' => $nullableArray,
            'invoice_date' => $requiredArray,
            'net_invoice_value' => $requiredArray,
            'document_submission_date' => $requiredArray,
            'submission_value' => $requiredArray,
            'realized_value' => $requiredArray,
            'short_realized_value' => $requiredArray,
            'due_realized_value' => $requiredArray,
            
            'document_submission_invoice_id.*' => $requiredInteger,
            'export_invoice_id.*' => $requiredInteger,
            'sales_contract_id.*' => $nullableInteger,
            'export_lc_id.*' => $nullableInteger,
            'invoice_date.*' => $requiredDate,
            'net_invoice_value.*' => $requiredNumeric,
            'document_submission_date.*' => $requiredDate,
            'submission_value.*' => $requiredNumeric,
            'realized_value.*' => ['required', 'numeric', new CommercialRealizedValueRule()],
            'short_realized_value.*' => $requiredNumericMinZero,
            'due_realized_value.*' => $requiredNumericMinZero,
        ];
    }
}