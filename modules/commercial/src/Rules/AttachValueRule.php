<?php


namespace SkylarkSoft\GoRMG\Commercial\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContractDetail;

class AttachValueRule implements Rule
{
    private $message;
    private $contract;

    public function __construct($contract)
    {
        $this->message = '';
        $this->contract = $contract;
    }

    public function passes($attribute, $value)
    {
        $idx = explode('.', $attribute)[1];


        $purchaseOrderId = request('po_id.' . $idx);

        $contractId = request()->route('contract')->id;

        $poValue = request('po_value.' . $idx);

        $contractValue = $this->contract->contract_value ?? 0;
        $previousContractValue = SalesContractDetail::query()
            ->where('sales_contract_id',  $contractId)
            ->where('po_id', '<>', $purchaseOrderId)
            ->sum('attach_value');

        $salesValue = SalesContractDetail::query()
            ->where('sales_contract_id', '<>', $contractId)
            ->where('po_id', $purchaseOrderId)
            ->sum('attach_value');


        if ($poValue < ($salesValue + $value)) {
            $this->message = 'Value exceeds for this PO attachment';
            return false;
        } elseif (sprintf('%0.2f', $contractValue) < sprintf('%0.2f', ($previousContractValue + $value))) {
            $this->message = 'Value exceeds from contract value';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
