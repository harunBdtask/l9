<?php


namespace SkylarkSoft\GoRMG\Commercial\Forms;

class SalesContractAmendmentForm extends Form
{
    public function persist()
    {
        // TODO: Implement persist() method.
    }

    public function rules(): array
    {
        return [
            'amendment_date' => 'required',
            'amendment_value' => 'required',
            'value_changed_by' => 'required',
            'last_shipment_date' => 'required',
            'expiry_date' => 'required',
            'shipping_mode' => 'required',
//            'inco_term' => 'required',
//            'inco_term_place' => 'required',
//            'port_of_entry' => 'required',
        ];
    }
}
