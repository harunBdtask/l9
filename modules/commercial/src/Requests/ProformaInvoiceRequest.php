<?php

namespace SkylarkSoft\GoRMG\Commercial\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProformaInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_category' => 'required',
            'importer_id' => 'required',
            'supplier_id' => 'required',
            'pi_receive_date' => 'required|date',
            'last_shipment_date' => 'nullable|date',
            'pi_validity_date' => 'nullable|date',
            'currency' => 'required',
            'source' => 'required',
            'hs_code' => 'required|string|max:30',
            'pi_basis' => 'required',
            'indentor_name' => 'nullable|string|max:30',
            'internal_file_no' => 'nullable',
            'lc_group_no' => 'nullable|string|max:30',
            'remarks' => 'nullable',
            'file' => 'nullable',
            'bill_entry_file' => 'nullable',
            'import_docs' => 'nullable',
            'pi_no' => 'required',
            'pay_term' => 'required|string|max:30',
            'goods_rcv_status' => $this->input('pi_basis') === 1 ? 'required' : 'nullable',

        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required',
            'max' => 'Max chars :max',
        ];
    }
}
