<?php


namespace SkylarkSoft\GoRMG\Inventory\Services;

use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class TrimsSearchFromPI implements TrimsInfoSearchInterface
{

    public $data = [];

    public $bookingCache = [];

    const ACCESSORIES_ID = 4;

    public function search(): array
    {

        $invoices = $this->getPIData();

        $formattedData = [];

        foreach ($invoices as $invoice) {

            if (!$invoice->details || !$invoice->details->details) {
                continue;
            }

            $details = $invoice->details->details;

            $inv['pi_no'] = $invoice->pi_no;
            $inv['pi_date'] = $invoice->pi_receive_date;
            $inv['pi_basis'] = $this->getPIBasis($invoice->pi_basis);
            $inv['supplier_id'] = $invoice->supplier_id;
            $inv['supplier'] = $invoice->supplier->name;
            $inv['internal_file_no'] = $invoice->internal_file_no;
            $inv['last_shipment_date'] = null;
            $inv['currency'] = $invoice->currency;
            $inv['source'] = $invoice->source;
            $inv['source_value'] = $invoice->getSource();

            $bookingsId = collect($details)->pluck('booking_id')->unique()->values();

            $bookingsIdAndStyleKeyValue = TrimsBookingDetails::whereIn('booking_id', $bookingsId)
                ->pluck('style_name', 'booking_id')
                ->all();

            foreach ($details as $detail) {

                $styleName = array_key_exists($detail->booking_id, $bookingsIdAndStyleKeyValue) ? $bookingsIdAndStyleKeyValue[$detail->booking_id] : '';
                $balance = $detail->quantity;

                $inv['details'][] = [
                    'sensitivity'                  => null,
                    'ship_date'                    => $detail->shipment_date,
                    'style_name'                   => $styleName,
                    'po_no'                        => collect($detail->po_no)->values(),
                    'ref_no'                       => '',
                    'brand_sup_ref'                => $detail->brand_or_supplier_ref,
                    'item_id'                      => $detail->item_id,
                    'item_name'                    => $detail->item_group,
                    'item_description'             => $detail->item_description,
                    'gmts_sizes'                   => $detail->gmts_size,
                    'item_color'                   => $detail->item_color,
                    'item_size'                    => $detail->item_size,
                    'uom'                          => $detail->uom,
                    'uom_id'                       => $detail->uom_id,
                    'wo_pi_qty'                    => $detail->quantity,
                    'receive_qty'                  => $balance,
                    'rate'                         => $detail->rate,
                    'amount'                       => $detail->amount,
                    'reject_qty'                   => null,
                    'payment_for_over_receive_qty' => null,
                    'floor'                        => null,
                    'room'                         => null,
                    'rack'                         => null,
                    'shelf'                        => null,
                    'bin'                          => null
                ];
            }

            $formattedData[] = $inv;
        }

        return $formattedData;
    }

    private function getPIData()
    {
        $supplierId = request('supplier_id');
        $piNo = request('pi_no');
        $fromDate = request('form');
        $toDate = request('to');


        return ProformaInvoice::with('supplier:id,name')->where('pi_no', $piNo)
            ->where('item_category', self::ACCESSORIES_ID)
            ->when($supplierId, function ($q) use ($supplierId) {
                return $q->where('supplier_id', $supplierId);
            })
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('pi_receive_date', [$fromDate, $toDate]);
            })
            ->get();
    }


    private function getPIBasis($basisId): ?string
    {
        if ($basisId == 1) {
            return 'Work Order Based';
        }

        if ($basisId == 2) {
            return 'Independent';
        }

        return '';
    }
}
