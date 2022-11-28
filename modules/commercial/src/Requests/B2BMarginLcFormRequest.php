<?php


namespace SkylarkSoft\GoRMG\Commercial\Requests;

use Illuminate\Foundation\Http\FormRequest;

class B2BMarginLcFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'application_date' => 'required',
            'lien_bank_id' => 'required',
            'item_id' => 'required',
            'lc_basis' => 'required',
            'pi_value' => 'required',

            'supplier_id' => 'required',
            'lc_type' => 'required',
            'last_shipment_date' => 'required',
            'lc_expiry_date' => 'required',
            'tenor' => 'required',

           'garments_qty' => 'required',
            'unit_of_measurement_id' => 'required',
            'partial_shipment' => 'required',
            'transhipment' => 'required',
            'add_confirmation_req' => 'required',
            // 'bonded_warehouse' => 'required',
            'hs_code' => 'required',
            'status' => 'required',
        ];
    }
}
