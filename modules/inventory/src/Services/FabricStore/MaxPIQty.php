<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore;

use SkylarkSoft\GoRMG\Commercial\Models\ProformaFabricDetail;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class MaxPIQty implements MaxReceiveQtyInterface
{

    public function maxReceiveQty($item)
    {

        $proformaInvoiceId = request('receivable_id');
        $styleName = Order::findOrFail($item['style_id'])->style_name;

        return ProformaFabricDetail::where([
            'proforma_invoice_id'   => $proformaInvoiceId,
            'garments_item_id'      => $item['gmts_item_id'],
            'body_part_id'          => $item['body_part_id'],
            'color_type_id'         => $item['color_type_id'],
            'construction'          => $item['construction'],
            'uom_id'                => $item['uom_id'],
            'fabric_composition_id' => $item['fabric_composition_id'],
            'dia_type'              => $item['dia_type'],
            'style_name'            => $styleName,
            'dia'                   => $item['dia'],
            'color_id'              => $item['color_id']
        ])->sum('quantity');
    }
}