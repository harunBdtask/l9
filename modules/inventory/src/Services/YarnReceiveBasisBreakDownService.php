<?php namespace SkylarkSoft\GoRMG\Inventory\Services;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class YarnReceiveBasisBreakDownService
{
    protected $data, $type, $basis_id;
    public function __construct($type, $basis_id)
    {
        $this->basis_id = $basis_id;
        $this->type = $type;
    }

    protected function receive_details($basis_detail_unique=null): array
    {
        $receiveDetailsQuery=YarnReceiveDetail::query()->where('basis_details_unique', $basis_detail_unique);
        return [
            'yarn_lot'=> optional($receiveDetailsQuery->first())->yarn_lot,
            'total_qty'=> $receiveDetailsQuery->selectRaw("SUM(receive_qty) as total_qty")->first()->total_qty,
        ];
    }
    protected function pi_basis(): Collection
    {
        $self=$this;
        $pi = ProformaInvoice::query()->find($this->basis_id);
        return collect(optional($pi->details)->details)->map(function ($item) use ($self, $pi){
            // Doing this because the format of pi details is horrible. Sometimes the key is missing sometimes different (-_-).
            $uomId = $item->uom_id ?? $item->uom ?? null;
            $countId = $item->yarn_count_id ?? $item->count_id ?? null;
            $buyerId = $item->buyer_id ?? null;
            $compositionId = $item->yarn_composition_id ?? $item->composition_id ?? null;
            $color = $item->yarn_color ?? $item->color ?? null;

            $yarnComposition = optional(YarnComposition::query()->find($compositionId))->yarn_composition;
            $yarnCount = optional(YarnCount::query()->find($countId))->yarn_count;
            $uom_name = optional(UnitOfMeasurement::query()->find($uomId))->unit_of_measurement;

            return [
                'buyer_id'              => $buyerId,
                'yarn_lot'              => isset($item->uuid)?$self->receive_details($item->uuid)['yarn_lot']:'',
                'basis_details_unique'  => $item->uuid??0,
                'uom_name'              => $uom_name,
                'uom_id'                => $uomId,
                'rate'                  => $item->rate ?? '',
                'yarn_color'            => $color,
                'basis_qty'             => $item->quantity ?? 0,
                'used_receive_qty'      => isset($item->uuid)?$self->receive_details($item->uuid)['total_qty']:0,
                'balance_qty'           => isset($item->uuid)?$item->quantity-$self->receive_details($item->uuid)['total_qty']:0,
                'receive_qty'           => isset($item->uuid)?$item->quantity-$self->receive_details($item->uuid)['total_qty']:0,
                'yarn_count_id'         => $countId,
                'supplier_id'           => $pi->supplier_id,
                'yarn_count_name'       => $yarnCount,
                'yarn_composition_id'   => $compositionId,
                'yarn_composition_name' => $yarnComposition,
                'yarn_type_id'          => $item->type ?? '',
                'yarn_type_name'        => $item->type_value ?? ''
            ];
        });
    }

    protected function wo_basis(): Collection
    {
        $self=$this;
        $wo=YarnPurchaseOrder::query()->find($this->basis_id);
        return collect($wo->details)->map(function ($item) use($self,$wo) {
            $yarn_type=CompositionType::query()->where('name', $item->yarn_type)->first();
            $uom_name=optional(UnitOfMeasurement::query()->find($item->uom_id))->unit_of_measurement;
            return [
                'uom_name'              => $uom_name,
                'yarn_lot'              => $self->receive_details($item->id)['yarn_lot'],
                'basis_details_unique'  => $item->id,
                'yarn_composition_name' => optional($item->yarnComposition)->yarn_composition,
                'yarn_count_name'       => optional($item->yarnCount)->yarn_count,
                'yarn_composition_id'   => $item->yarn_composition_id,
                'yarn_count_id'         => $item->yarn_count_id,
                'yarn_type_name'        => optional($yarn_type)->name,
                'yarn_type_id'          => optional($yarn_type)->id,
                'supplier_id'           => $wo->supplier_id,
                'yarn_color'            => $item->yarn_color,
                'buyer_id'              => $item->buyer_id,
                'basis_qty'             => $item->wo_qty,
                'receive_qty'           => $item->wo_qty-$self->receive_details($item->id)['total_qty']??0,
                'used_receive_qty'      => $self->receive_details($item->id)['total_qty']??0,
                'balance_qty'           => $item->wo_qty-$self->receive_details($item->id)['total_qty']??0,
                'uom_id'                => $item->uom_id,
                'rate'                  => $item->rate,
            ];
        });
    }

    public function output()
    {
        if ($this->type=='pi'){
            return $this->pi_basis();
        }
        if($this->type=='wo'){
            return $this->wo_basis();
        }
        return [];
    }


}
