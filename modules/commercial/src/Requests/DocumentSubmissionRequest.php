<?php


namespace SkylarkSoft\GoRMG\Commercial\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'documentSubmission.factory_id' => 'required',
            'documentSubmission.buyer_id' => 'required',
            'documentSubmission.submission_date' => 'required',
            'documentSubmission.submission_type' => 'required',
            'documentSubmission.lc_sc_no' => 'required',
            'documentSubmission.submitted_to' => 'required',
            'documentSubmission.negotiation_date' => 'required_if:documentSubmission.submission_type,==,2',
            'documentSubmission.bank_ref_bill' => [
                Rule::unique('document_submissions', 'bank_ref_bill')
                ->whereNull('deleted_at')
                ->ignore($this->route('documentSubmission')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required',
            'documentSubmission.factory_id.required' => 'Factory Field Is Required',
            'documentSubmission.buyer_id.required' => 'Buyer Field Is Required',
            'documentSubmission.submission_date.required' => 'Submission Date Field Is Required',
            'documentSubmission.negotiation_date.required' => 'Negotiation Field Is Required',
            'documentSubmission.bank_ref_bill' => 'Unique Bank Ref Bill',
            'documentSubmission.submission_type' => 'Submission Type Field Is Required',
            'documentSubmission.submitted_to' => 'Submitted To Field Is Required',
            'documentSubmission.lc_sc_no' => 'LC SC NO Field Is Required',
        ];
    }
}
