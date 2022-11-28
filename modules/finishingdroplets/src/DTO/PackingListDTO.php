<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\DTO;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class PackingListDTO
{
    private $poId;
    private $colorSizePoDetails;
    private $sizes;

    public function getPoId()
    {
        return $this->poId;
    }

    public function setPoId($poId): self
    {
        $this->poId = $poId;
        return $this;
    }

    public function setSizes($sizes): self
    {
        $this->sizes = $sizes;
        return $this;
    }

    public function getSizes()
    {
        return $this->sizes;
    }


    public function getPoDetails(): self
    {
        $poDetails = PurchaseOrderDetail::query()
            ->with(['color', 'size:id,name', 'buyer', 'order', 'po'])
            ->where('purchase_order_id', $this->poId)
            ->get();

        $this->sizes = $poDetails->unique('size_id')->pluck('size');

        $this->colorSizePoDetails = $poDetails
            ->groupBy('color_id');

        return $this;
    }

    public function setPoDetails($poDetails): self
    {
        $this->sizes = $poDetails->unique('size_id')->pluck('size');

        $this->colorSizePoDetails = $poDetails
            ->groupBy('color_id');

        return $this;
    }

    public function format(): array
    {

        $data = [];
        $i = 0;
        foreach ($this->colorSizePoDetails as $key => $poDetails) {
            $firstPoDetails = $poDetails->first();
            $data[$i] = [
                'buyer_id' => $firstPoDetails->buyer_id,
                'order_id' => $firstPoDetails->order_id,
                'purchase_order_id' => $firstPoDetails->purchase_order_id,
                'color_id' => $firstPoDetails->color_id,
                'buyer' => $firstPoDetails->buyer->name,
                'style' => $firstPoDetails->order->style_name,
                'po' => $firstPoDetails->po->po_no,
                'color' => $firstPoDetails->color->name,
                'assort_solid' => $firstPoDetails->order->packing_ratio ?? null,
                'destination' => null,
                'tag_type' => null,
                'no_of_carton' => null,
                'qty_per_carton' => null,
                'no_of_boxes' => null,
                'blister_kit_carton' => null,
                'kit_bc_carton' => null,
                'carton_no_from' => null,
                'carton_no_to' => null,
                'measurement_l' => null,
                'measurement_w' => null,
                'measurement_h' => null,
                'bc_height' => null,
                'gw_box_weight' => null,
                'bc_gw' => null,
                'nw_box_weight' => null,
                'bc_nw' => null,
                'm3_cbu' => null,
                'type_of_shipment' => null,
                'order_qty' => $poDetails->sum('quantity') ?? 0,
                'sizes' => []
            ];

            foreach ($this->sizes as $size) {
                $data[$i]['sizes'][$size->name] = [
                    'size' => $size->name,
                    'size_id' => $size->id,
                    'qty' => $poDetails->where('size_id', $size->id)->sum('quantity') ?? 0
                ];
            }
            $i++;
        }

        return $data;

    }

    public function editFormat(): Collection
    {
        $data = [];
        $i = 0;
        foreach ($this->colorSizePoDetails as $key => $poDetails) {
            $firstPoDetails = $poDetails->first();
            $data[$i] = [
                'buyer_id' => $firstPoDetails->buyer_id,
                'order_id' => $firstPoDetails->order_id,
                'purchase_order_id' => $firstPoDetails->purchase_order_id,
                'color_id' => $firstPoDetails->color_id,
                'buyer' => $firstPoDetails->buyer->name,
                'company' => $firstPoDetails->factory->factory_name,
                'style' => $firstPoDetails->order->style_name,
                'po' => $firstPoDetails->purchaseOrder->po_no,
                'color' => $firstPoDetails->color->name,
                'assort_solid' => $firstPoDetails->order->packing_ratio ?? null,
                'destination' => $firstPoDetails->destination ?? null,
                'tag_type' => $firstPoDetails->tag_type ?? null,
                'tag_type_value' => $firstPoDetails->tag_type_value ?? null,
                'no_of_carton' => $firstPoDetails->no_of_carton ?? null,
                'qty_per_carton' => $firstPoDetails->qty_per_carton ?? null,
                'no_of_boxes' => $firstPoDetails->no_of_boxes ?? null,
                'blister_kit_carton' => $firstPoDetails->blister_kit_carton ?? null,
                'kit_bc_carton' => $firstPoDetails->kit_bc_carton ?? null,
                'carton_no_from' => $firstPoDetails->carton_no_from ?? null,
                'carton_no_to' => $firstPoDetails->carton_no_to ?? null,
                'measurement_l' => $firstPoDetails->measurement_l ?? null,
                'measurement_w' => $firstPoDetails->measurement_w ?? null,
                'measurement_h' => $firstPoDetails->measurement_h ?? null,
                'bc_height' => $firstPoDetails->bc_height ?? null,
                'gw_box_weight' => $firstPoDetails->gw_box_weight ?? null,
                'bc_gw' => $firstPoDetails->bc_gw ?? null,
                'nw_box_weight' => $firstPoDetails->nw_box_weight ?? null,
                'bc_nw' => $firstPoDetails->bc_nw ?? null,
                'm3_cbu' => $firstPoDetails->m3_cbu ?? null,
                'type_of_shipment' => $firstPoDetails->type_of_shipment ?? null,
                'type_of_shipment_value' => $firstPoDetails->type_of_shipment_value ?? null,
                'order_qty' => $poDetails->sum('size_wise_qty') ?? 0,
                'sizes' => []
            ];

            foreach ($this->sizes as $size) {
                $data[$i]['sizes'][$size->name] = [
                    'size' => $size->name,
                    'size_id' => $size->id,
                    'qty' => $poDetails->where('size_id', $size->id)->sum('size_wise_qty') ?? 0
                ];
            }
            $i++;
        }

        return collect($data)->values();

    }
}
