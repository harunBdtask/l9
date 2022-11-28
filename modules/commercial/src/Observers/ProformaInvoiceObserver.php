<?php

namespace SkylarkSoft\GoRMG\Commercial\Observers;

use SkylarkSoft\GoRMG\Commercial\Models\ProformaFabricDetail;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class ProformaInvoiceObserver
{
    /**
     * Handle the ProformaInvoice "created" event.
     *
     * @param  ProformaInvoice $proformaInvoice
     * @return void
     */
    public function created(ProformaInvoice $proformaInvoice)
    {
        //
    }

    /**
     * Handle the ProformaInvoice "created" event.
     *
     * @param  ProformaInvoice $proformaInvoice
     * @return void
     */
    public function saved(ProformaInvoice $proformaInvoice)
    {
        $this->updateProformaInvoiceFabricDetails($proformaInvoice);
    }

    private function updateProformaInvoiceFabricDetails($proformaInvoice)
    {
        $fabric_item_category_query = Item::query()->where('item_name', 'Knit Finish Fabrics')->first();
        $item_category = $fabric_item_category_query ? $fabric_item_category_query->id : null;
        $work_order_pi_basis = 1;
        $proforma_invoice_id = $proformaInvoice->id;
        ProformaFabricDetail::where('proforma_invoice_id', $proformaInvoice->id)->delete();
        if ($item_category && $proformaInvoice->pi_basis == $work_order_pi_basis  && $proformaInvoice->item_category == $item_category && $proformaInvoice->details) {
            if ($proformaInvoice->details->details && count($proformaInvoice->details->details)) {
                foreach ($proformaInvoice->details->details as $key => $obj) {
                    $purchase_order_ids = $obj->purchase_order_ids ? explode(',', $obj->purchase_order_ids) : null;
                    $contrast_color_id = $obj->contrast_color_id ? explode(',', $obj->contrast_color_id) : null;
                    $details_id = $obj->details_id ?? null;
                    $color_type_id = null;
                    $color_type_value = null;
                    if ($details_id) {
                        $fabric_booing_details_breakdown = FabricBookingDetailsBreakdown::query()->findOrFail($obj->details_id);
                        $color_type_id = $fabric_booing_details_breakdown->color_type_id ?? null;
                        $color_type_value = $fabric_booing_details_breakdown->color_type_value ?? null;
                    }
                    $piFabricDetails = new ProformaFabricDetail([
                        'proforma_invoice_id' => $proforma_invoice_id,
                        'gsm' => $obj->gsm ?? null,
                        'uom' => $obj->uom ?? null,
                        'uom_id' => $obj->uom_id ?? null,
                        'rate' => $obj->rate ?? null,
                        'type' => $obj->type ?? null,
                        'color' => $obj->color ?? null,
                        'color_id' => $obj->color_id ?? null,
                        'po_nos' => $obj->po_nos ?? null,
                        'purchase_order_ids' => $purchase_order_ids ?? null,
                        'wo_no' => $obj->wo_no ?? null,
                        'amount' => $obj->amount ?? null,
                        'hs_code' => $obj->hs_code ?? null,
                        'buyer_id' => $obj->buyer_id ?? null,
                        'buyer_name' => $obj->buyer_name ?? null,
                        'body_part' => $obj->body_part ?? null,
                        'body_part_id' => $obj->body_part_id ?? null,
                        'quantity' => $obj->quantity ?? null,
                        'dia' => $obj->dia ?? null,
                        'dia_type' => $obj->dia_type ?? null,
                        'dia_type_value' => $obj->dia_type_value ?? null,
                        'unique_id' => $obj->unique_id ?? null,
                        'booking_id' => $obj->booking_id ?? null,
                        'details_id' => $details_id,
                        'style_name' => $obj->style_name ?? null,
                        'composition' => $obj->composition ?? null,
                        'construction' => $obj->construction ?? null,
                        'style_unique_id' => $obj->style_unique_id ?? null,
                        'order_id' => $obj->order_id ?? null,
                        'fabric_composition_id' => $obj->fabric_composition_id ?? null,
                        'contrast_color_id' => $contrast_color_id ?? null,
                        'contrast_colors' => $obj->contrast_colors ?? null,
                        'garments_item_id' => $obj->garments_item_id ?? null,
                        'garments_item' => $obj->garments_item ?? null,
                        'color_type_id' => $color_type_id,
                        'color_type' => $color_type_value,
                    ]);
                    $piFabricDetails->save();
                }
            }
        }
    }

    /**
     * Handle the ProformaInvoice "updated" event.
     *
     * @param  ProformaInvoice $proformaInvoice
     * @return void
     */
    public function updated(ProformaInvoice $proformaInvoice)
    {
        //
    }

    /**
     * Handle the ProformaInvoice "deleted" event.
     *
     * @param  ProformaInvoice $proformaInvoice
     * @return void
     */
    public function deleted(ProformaInvoice $proformaInvoice)
    {
        //
    }

    /**
     * Handle the ProformaInvoice "restored" event.
     *
     * @param  ProformaInvoice $proformaInvoice
     * @return void
     */
    public function restored(ProformaInvoice $proformaInvoice)
    {
        //
    }

    /**
     * Handle the ProformaInvoice "force deleted" event.
     *
     * @param  ProformaInvoice $proformaInvoice
     * @return void
     */
    public function forceDeleted(ProformaInvoice $proformaInvoice)
    {
        //
    }
}
