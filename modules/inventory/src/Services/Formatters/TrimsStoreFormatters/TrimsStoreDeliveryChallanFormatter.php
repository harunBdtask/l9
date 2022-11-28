<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\Formatters\TrimsStoreFormatters;

use SkylarkSoft\GoRMG\Inventory\Services\TrimsStore\BookingDataApiService;

class TrimsStoreDeliveryChallanFormatter
{
    public static function format($challans): array
    {
        return $challans->map(function ($challan) {
            $bookingData = BookingDataApiService::get($challan->booking_no);
            $challanDetailsCollection = collect($challan->details);
            return [
                'id' => $challan->id,
                'issue_no' => $challanDetailsCollection->pluck('issueDetail.issue.unique_id')->unique()->values()->join(', '),
                'factory_id' => $challan->factory_id,
                'buyer_id' => $challan->buyer_id,
                'buyer_name' => $challan->buyer->name ?? '',
                'store_id' => $challan->store_id,
                'store_name' => $challan->store->name ?? '',
                'booking_id' => $challan->booking_id,
                'booking_no' => $challan->booking_no,
                'challan_no' => $challan->challan_no,
                'challan_type' => $challan->challan_type,
                'challan_date' => $challan->challan_date,
                'challan_qty' => $challanDetailsCollection->pluck('issueDetail')->sum('issue_qty'),
                'booking_qty' => $challan->booking_qty,
                'excess_delivery_qty' => $challan->excess_delivery_qty,
                'pi_no' => $challan->pi_no,
                'supplier_name' => $bookingData['supplier_name'],
                'delivery_to' => $bookingData['delivery_to'],
                'style_name' => $bookingData['style_name'],
                'po_no' => $bookingData['po_no'],
                'booking_date' => $bookingData['booking_date'],
                'delivery_address' => $bookingData['delivery_address'],
                'attention' => $bookingData['attention'],
                'dealing_merchant' => $bookingData['dealing_merchant'],
                'season_name' => $bookingData['season_name'],
                'created_at' => $challan->created_at,
                'short_access_qty' => $challan->booking_qty - $challanDetailsCollection->pluck('issueDetail')->sum('issue_qty'),
                'details' => $challanDetailsCollection->map(function ($detail) use ($challanDetailsCollection) {
                    $totalIssueQty = $challanDetailsCollection->pluck('issueDetail')->sum('issue_qty');
                    $issueQty = $detail->issueDetail->issue_qty;
                    $totalDeliveryChallanQty = $detail->where('trims_store_issue_detail_id', $detail->trims_store_issue_detail_id)
                        ->sum('issue_qty');

                    return [
                        'id' => $detail->id,
                        'trims_store_issue_detail_id' => $detail->trims_store_issue_detail_id,
                        'factory_id' => $detail->factory_id,
                        'item_id' => $detail->item_id,
                        'store_id' => $detail->store_id,
                        'uom_id' => $detail->uom_id,
                        'item_name' => $detail->itemGroup->item_group ?? '',
                        'item_description' => $detail->item_description,
                        'color' => $detail->color->name ?? '',
                        'size' => $detail->size,
                        'approval_shade_code' => $detail->approval_shade_code,
                        'planned_garments_qty' => $detail->planned_garments_qty,
                        'floor' => $detail->floor->name ?? '',
                        'room' => $detail->room->name ?? '',
                        'rack' => $detail->rack->name ?? '',
                        'shelf' => $detail->shelf->name ?? '',
                        'bin' => $detail->bin->name ?? '',
                        'uom_value' => $detail->uom->unit_of_measurement ?? '',
                        'issue_qty' => $detail->issue_qty,
                        'issue_date' => $detail->issue_date,
                        'issue_return_date' => $detail->issue_return_date,
                        'issue_return_qty' => $detail->issue_return_qty,
                        'total_issue_qty' => $totalIssueQty,
                        'issue_balance' => $issueQty - $totalDeliveryChallanQty,
                        'issue_purpose' => $detail->issue_purpose,
                        'issue_to' => $detail->issue_to,
                        'remarks' => $detail->remarks,
                    ];
                }),
            ];
        })->toArray();
    }
}
