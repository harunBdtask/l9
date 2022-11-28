<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaFabricDetail;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class PISearchForFabricReceive
{
    public function getData($request): array
    {
        $request = $request ?? request();
        $data = [];
        $pi_no = $request->booking_no;
        $order_no = $request->order_no;
        $style_name = $request->style_name;

        $item_category_query = Item::query()->where('item_name', 'Knit Finish Fabrics')->first();
        $item_category = $item_category_query ? $item_category_query->id : null;
        if ($item_category && ($pi_no || $order_no || $style_name)) {
            $pi_ids_from_po = [];
            if($order_no) {
                $po_query = PurchaseOrder::query()->where('po_no', $order_no)->first();
                $po_id = $po_query ? $po_query->id : null;
                if ($po_id) {
                    $pi_ids_from_po = ProformaFabricDetail::query()->whereRaw('json_contains(purchase_order_ids, "[\"'.$po_id.'\"]")')->pluck('proforma_invoice_id')->unique('proforma_invoice_id')->toArray();
                }
            } 
            $pi_ids_from_style = $style_name ? ProformaFabricDetail::query()->where('style_name', $style_name)->pluck('proforma_invoice_id')->toArray() : [];
            $pi_ids = array_unique(array_merge($pi_ids_from_po, $pi_ids_from_style)) ?? [];
            if ($pi_no || ($pi_ids && is_array($pi_ids) && count($pi_ids))) {
                $data = ProformaInvoice::query()
                ->where('item_category', $item_category)
                ->when($pi_no, function($query) use($pi_no) {
                    $query->where('pi_no', $pi_no);
                })
                ->when(($pi_ids && is_array($pi_ids) && count($pi_ids)), function($query) use($pi_ids) {
                    $query->whereIn('id', $pi_ids);
                })
                ->get()
                ->map(function ($item) {
                    return $this->formatData($item);
                })->toArray();
            }
        }
        return $data;
    }


    public function formatData($item): array
    {
        $pi_basis_options = [
            1 => 'Work Order Based',
            2 => 'Independent'
        ];

        $piBasis = array_key_exists($item->pi_basis, $pi_basis_options) ? $pi_basis_options[$item->pi_basis] : null;

        $pi_basis = $item->pi_basis ? $piBasis : null;

        $sources = [
            1 => 'Abroad',
            2 => 'EPZ',
            3 => 'Non-EPZ'
        ];

        $source = array_key_exists($item->source, $sources) ? $sources[$item->source] : null;

        $source = $item->source ? $source : null;

        return [
            'pi_id'         => $item->id,
            'pi_no'         => $item->pi_no ?? null,
            'pi_date'       => $item->pi_receive_date ?? null,
            'pi_basis'      => $pi_basis,
            'supplier'      => $item->supplier->name ?? null,
            'shipment_date' => $item->last_shipment_date ?? null,
            'file_no'       => $item->internal_file_no ?? null,
            'currency'      => $item->currency ?? null,
            'source'        => $source,
            'details'       => $item->details ?? null
        ];
    }
}
