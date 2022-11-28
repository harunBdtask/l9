<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AcSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'control_account_id' => 'required',
            'group_company' => 'required',
            'name' => 'required',
//            'ledger_account_id' => 'required_if:group_company, == , 1',
            'ledger_account_name' => 'required_if:group_company, == , 2',
            'sub_ledger_account_name' => 'required_if:group_company, == , 1',
            'contract_information.head_office' => 'required',
            'contract_information.branch_office' => 'required',
            'tax_vat_info.tax_tin_number' => 'required',
            'tax_vat_info.tax_rate' => 'required',
            'tax_vat_info.vat_tin_number' => 'required',
//            'tax_vat_info.vat_rate' => 'required',
            'tax_vat_info.vat_type' => 'required',
            'payment.mode' => 'required',
            'payment.condition' => 'required',
            'payment.payment_after' => 'required',
            'items' => [
                '*.item_group_id' => 'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tax_vat_info.tax_tin_number.required' => 'This tax tin number field is required',
            'tax_vat_info.tax_rate.required' => 'This tax rate field is required',
            'tax_vat_info.vat_tin_number.required' => 'This vat tin number field is required',
            'tax_vat_info.vat_rate.required' => 'This vat rate field is required',
            'tax_vat_info.vat_type.required' => 'This vat type field is required',
            'payment.mode.required' => 'This payment mode field is required',
            'payment.cheque_name.required' => 'This payment cheque name field is required',
            'payment.condition.required' => 'This payment condition field is required',
            'payment.payment_after.required' => 'This payment after field is required',
            'items.required' => 'At least one item mandatory',
            'contract_information.branch_office.required' => 'At least one branch office mandatory',
            'contract_information.head_office.required' => 'At least one head office mandatory',
        ];
    }
}
