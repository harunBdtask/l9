<?php

namespace SkylarkSoft\GoRMG\Commercial\Services\Commercial;

use SkylarkSoft\GoRMG\Commercial\Models\Lien\LienDetail;
use SkylarkSoft\GoRMG\Commercial\Models\SalesContract;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class LienDataService
{
    protected $lien;

    public function for($lien): LienDataService
    {
        $this->lien = $lien;
        return $this;
    }
    public function first(): array
    {
        $selfLien=$this->lien;
        return [
            'data' => [
                'id'=>$this->lien->id,
                'bank_id'=>$this->lien->bank_id,
                'lien_no'=>$this->lien->lien_no,
                'lien_date'=>$this->lien->lien_date,
                'factory_id'=>$this->lien->factory_id,
            ],
            'details' => $this->lien->details->map(function ($item) {
                return [
                    'id' => $item->id,
                    'lien_id'=>$item->lien_id,
                    'buyer_id' => $item->buyer_id,
                    'buyer_name' => $item->buyer_name,
                    'internal_file_no' => $item->internal_file_no,
                    'sales_contract_no' => $item->sales_contract_no,
                    'sales_contract_id' => $item->sales_contract_id,
                    'sales_contract_date' => $item->sales_contract_date,
                    'sales_contract_value' => $item->sales_contract_value,
                ];
            }),
            'contacts' => SalesContract::query()->with('buyer')->get()->map(function ($item) use($selfLien){
              $lienDetail=LienDetail::query()
                    ->where('lien_id', $selfLien->id)
                    ->where('sales_contract_id', $item->id)
                    ->first();
                return [
                    'id' => optional($lienDetail)->id,
                    'lien_id'=> optional($lienDetail)->lien_id,
                    'buyer_id' => $item->buyer_id,
                    'sales_contract_id' => $item->id,
                    'buyer_name' => $item->buyer->name,
                    'internal_file_no' => $item->internal_file_no,
                    'sales_contract_date' => $item->contract_date,
                    'sales_contract_no' => $item->contract_number,
                    'sales_contract_value' => $item->contract_value,
                ];
            })
        ];
    }
}
