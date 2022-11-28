<?php


namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountingRealizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'realization_type_source' => 'required',
            'factory_id' => 'required',
            'bf_project_id' => 'required',
            'bf_unit_id' => 'required',
            'realization_type' => 'required',
            'document_submission_id' => 'nullable',
            'commercial_realization_id' => 'nullable',
            'realization_number' => 'required',
            'export_lc_id' => 'nullable|array',
            'sales_contract_id' => 'nullable|array',
            'export_invoice_id' => 'nullable|array',
            'sc_number' => 'nullable|array',
            'lc_number' => 'nullable|array',
            'realization_date' => 'required|date',
            'realization_rate' => 'required|numeric',
            'currency_id' => 'required',
            'total_value' => 'required|array',
            'realized_value' => 'required|array',
            'short_realization' => 'required|array',
            'foreign_bank_charge' => 'required|array',
            'deduction' => 'required|array',
            'total_deduction' => 'required|array',
            'distribution' => 'required|array',
            'loan_distribution' => 'nullable|array',
            'total_distribution' => 'nullable|array',
            'grand_total' => 'nullable|array',
            'realized_gain_loss' => 'nullable',
            'realized_difference' => 'nullable',
        ];
    }
}
