<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaFabricDetail;

class PIDetailsForFabricReceive
{

    public function getData(Request $request): array
    {
        $pi_id = $request->pi_id;
        $data = [];
        if ($pi_id) {
            $data = ProformaFabricDetail::query()
                ->where('proforma_invoice_id', $pi_id)
                ->get()
                ->map(function ($item) {
                    return [
                        'unique_id' => $item->style_unique_id ?? null,
                        'buyer_id' => $item->buyer_id ?? null,
                        'style_id' => $item->order_id ?? null,
                        'style_name' => $item->style_name ?? null,
                        'batch_no' => null,
                        'gmts_item_id' => $item->garments_item_id ?? null,
                        'gmts_item_name' => $item->garments_item?? null,
                        'body_part_id' => $item->body_part_id ?? null,
                        'body_part_value' => $item->body_part ?? null,
                        'fabric_composition_id' => $item->fabric_composition_id ?? null,
                        'fabric_composition_value' => $item->composition ?? null,
                        'construction' => $item->construction ?? null,
                        'fabric_description' => null,
                        'dia' => $item->dia ?? null,
                        'gsm' => $item->gsm ?? null,
                        'dia_type' => $item->dia_type ?? null,
                        'dia_type_value' => $item->dia_type_value ?? null,
                        'color_id' => $item->color_id ?? null,
                        'color_name' => $item->color ?? null,
                        'contrast_color_id' => $item->contrast_color_id ?? null,
                        'contrast_color_name' =>$item->contrast_colors ?? null,
                        'uom_id' => $item->uom_id ?? null,
                        'uom_name' => $item->uom ?? null,
                        'receive_qty' => null,
                        'booking_qty' => $item->qty ?? null,
                        'rate' => $item->rate ?? null,
                        'amount' => $item->amount ?? null,
                        'reject_qty' => null,
                        'fabric_shade' => null,
                        'no_of_roll' => null,
                        'grey_used' => null,
                        'store_id' => null,
                        'floor_id' => null,
                        'room_id' => null,
                        'rack_id' => null,
                        'shelf_id' => null,
                        'remarks' => null,
                        'color_type_id' => $item->color_type_id ?? null,
                        'color_type' => $item->color_type ?? null,
                    ];
                })->toArray();
        }

        return $data;
    }
}
