<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssue;

use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\BalanceQty;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricStockSummary;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;

class FabricManualIssue implements FabricIssueContracts
{
    public function handle(FabricIssueStrategy $strategy)
    {
//        dd($strategy);
        $request = $strategy->getRequest();
        $firstDate = $request->get('first_date');
        $lastDate = $request->get('last_date');
        $type = $request->get('type');
        $code = $request->get('code');
        $storeId = $request->get('storeId');

        return FabricReceive::query()
            ->with([
                'details' => function ($query) use ($type, $code) {
                    $query->when($type === 'batch_no', function (Builder $query) use ($code) {
                        $query->where('batch_no', $code);
                    })->when($type === 'style_name', function (Builder $query) use ($code) {
                        $query->where('style_name', $code);
                    });
                },
                'details.issueDetails.issueReturnDetails'
            ])
            ->where('status', FabricReceive::APPROVE)
            ->when($firstDate && $lastDate, function (Builder $query) use ($firstDate, $lastDate) {
                $query->whereBetween('receive_date', [$firstDate, $lastDate]);
            })
            ->when($type === 'order_no', function ($query) use ($code) {
                $query->wherejsoncontains('po_no', $code);
            })
            ->when($type === 'receive_no', function ($query) use ($code) {
                $query->where('receive_no', $code);
            })
            ->when($type === 'receive_challan', function ($query) use ($code) {
                $query->where('receive_challan', $code);
            })
            ->when($type === 'booking_no', function (Builder $query) use ($code) {
                $query->whereHas('booking', function (Builder $query) use ($code) {
                    $query->where('unique_id', $code);
                });
            })
            ->get()->map(function ($receive) use ($storeId) {
                $batchNo = collect($receive->details)->pluck('batch_no')->unique()->join(' ,');
                $colorId = collect($receive->details)->pluck('color_id')->unique();
                $colors = Color::query()->whereIn('id', $colorId)->get();
                $colorNames = collect($colors)->pluck('name')->join(' ,');
                $bookingNo = collect($receive->details)->pluck('unique_id')->unique()->join(' ,');
                $style_name = $receive->details->first()['style_name'] ?? null;
                $batchQty = collect($receive->details)->sum('receive_qty');
                $poNos = collect($receive->details)->pluck('po_no')->unique()->join(', ');

                if (count($receive->details)) {
                    return [
                        'batch_no' => $batchNo,
                        'extension_no' => null,
                        'batch_date' => null,
                        'batch_qty' => $batchQty,
                        'booking_no' => $bookingNo,
                        'uniq_id' => $receive->receive_no,
                        'colors' => $colorNames,
                        'style_name' => $style_name,
                        'po_no' => $poNos,
                        'file_no' => null,
                        'ref_no' => null,
                        'details' => collect($receive->details)->map(function ($detail) use ($receive, $storeId) {

                            $previousIssueQty = $detail->issueDetails->sum('issue_qty');

                            $bodyPartValue = null;

                            if ($receive->receive_basis == FabricReceive::INDEPENDENT_BASIS) {
                                $bodyPartValue = $detail->body->name;
                            } else {
                                $bookingDetails = FabricBookingDetailsBreakdown::query()
                                    ->where('job_no', $detail->unique_id)
                                    ->first();

                                $bodyPartValue = $bookingDetails->body_part_value ?? '';
                            }

                            $balanceQty = (new BalanceQty)->balance($detail);

                            $purchaseOrders = PurchaseOrder::query()
                                ->whereIn('po_no', explode(',', $detail->po_no))
                                ->orderBy('ex_factory_date');

                            if ($balanceQty != 0) {
                                return [
                                    'id' => null,
                                    'fabric_receive_id' => $receive->id,
                                    'fabric_receive_details_id' => $detail->id,
                                    'store_id' => $storeId,
                                    'prod_id' => null,
                                    'style_id' => $detail->style_id,
                                    'style_name' => $detail->style_name,
                                    'po_no' => $detail->po_no,
                                    'construction' => $detail->construction,
                                    'unique_id' => $detail->unique_id,
                                    'batch_no' => $detail->batch_no,
                                    'fabric_color_id' => $detail->color_id,
                                    'fabric_color_name' => $detail->color->name,
                                    'fabric_shade' => $detail->fabric_shade,
                                    'fabric_description' => $detail->fabric_description,
                                    'dia' => $detail->dia,
                                    'ac_dia' => $detail->ac_dia,
                                    'gsm' => $detail->gsm,
                                    'ac_gsm' => $detail->ac_gsm,
                                    'dia_type' => $detail->dia_type,
                                    'ac_dia_type' => $detail->ac_dia_type,
                                    'color_id' => $detail->color_id,
                                    'color' => $detail->fabricColor->name,
                                    'sample_type' => 'Sample 1',
                                    'uom_id' => $detail->uom_id,
                                    'uom_name' => $detail->receive->receive_basis === FabricReceive::INDEPENDENT_BASIS
                                        ? $detail->uom->unit_of_measurement
                                        : FabricIssueDetail::UOM[$detail->uom_id],
                                    'floor_id' => $detail->floor_id,
                                    'floor_name' => $detail->floor->name,
                                    'room_id' => $detail->room_id,
                                    'room_name' => $detail->room->name,
                                    'rack_id' => $detail->rack_id,
                                    'rack_name' => $detail->rack->name,
                                    'shelf_id' => $detail->shelf_id,
                                    'shelf_name' => $detail->shelf->name,
                                    'receive_qty' => $detail->receive_qty,
                                    'previous_issue_qty' => $previousIssueQty,
                                    'issue_qty' => null,
                                    'balance_qty' => $balanceQty,
                                    'gmts_item_id' => $detail->gmts_item_id,
                                    'gmts_item_name' => $detail->item->name,
                                    'body_part_id' => $detail->body_part_id,
                                    'body_part_value' => $bodyPartValue ?? null,
                                    'rate' => $detail->rate,
                                    'no_of_roll' => $detail->no_of_roll,
                                    'cutting_unit_no' => null,
                                    'remarks' => null,
                                    'fabric_composition_id' => $detail->fabric_composition_id,
                                    'color_type_id' => $detail->color_type_id,
                                    'issue_type' => 'manual',
                                    'issue_qty_details' => [
                                        [
                                            'po_no' => $detail->po_no,
                                            'file_no' => null,
                                            'ref_no' => null,
                                            'shipment_date' => $purchaseOrders->first()['ex_factory_date'] ?? null,
                                            'po_qty' => $purchaseOrders->sum('po_quantity') ?? '0.000',
                                            'req_qty' => null,
                                            'receive_qty' => $detail->receive_qty,
                                            'balance_qty' => $balanceQty,
                                            'cumu_issue_qty' => $previousIssueQty,
                                            'no_of_roll' => $detail->no_of_roll,
                                            'issue_qty' => null,
                                        ]
                                    ],
                                ];
                            } else {
                                return false;
                            }
                        })->reject(function ($detail) {
                            return $detail === false;
                        })
                    ];
                } else {
                    return false;
                }
            })->reject(function ($receive) {
                return $receive === false;
            });
    }

    public function store(FabricIssueStrategy $strategy)
    {

        $request = $strategy->getRequest();
        $fabricIssue = $strategy->getIssueModel();

        $fabricIssueDetail = $fabricIssue->details()->findOrNew($request->id ?? null);
        $fabricIssueDetail->fill($request->all())->save();

        return $fabricIssueDetail;
    }
}
