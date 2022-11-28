<?php

namespace SkylarkSoft\GoRMG\Commercial\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLCDetail;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContractDetail;

class ExportLCAttachValueRule implements Rule
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

        $lc_value = $this->contract->lc_value ?? 0;
        $previousContractValue = ExportLCDetail::query()
            ->where('export_lc_id',  $contractId)
            ->where('po_id', '<>', $purchaseOrderId)
            ->sum('attach_value');

        $salesValue = ExportLCDetail::query()
            ->where('export_lc_id', '<>', $contractId)
            ->where('po_id', $purchaseOrderId)
            ->sum('attach_value');


        if ( sprintf('%0.2f', $poValue) < sprintf('%0.2f', ($salesValue + $value))  ) {
            $this->message = 'Value exceeds';

            return false;
        } elseif (sprintf('%0.2f', $lc_value) < sprintf('%0.2f', ($previousContractValue + $value))) {
            $this->message = 'Value exceeds from LC value';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
