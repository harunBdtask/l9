<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\DTO\OrderReportDTO;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class OrderReportService
{
    public static function reportData(OrderReportDTO $orderReportDTO): array
    {
        $factoryId = $orderReportDTO->getFactoryId();
        $buyerId = $orderReportDTO->getBuyerId();
        $dealingMerchantId = $orderReportDTO->getDealingMerchantId();
        $jobNo = $orderReportDTO->getJobNo();
        $styleName = $orderReportDTO->getStyleName();
        $poNo = $orderReportDTO->getPoNo();
        $searchType = $orderReportDTO->getSearchType();
        $fromDate = $orderReportDTO->getFromDate() ? date_format(date_create($orderReportDTO->getFromDate()), 'Y-m-d') : null;
        $toDate = $orderReportDTO->getToDate() ? date_format(date_create($orderReportDTO->getToDate()), 'Y-m-d') : null;

        $purchase_orders = PurchaseOrder::query()
            ->with(
                'poDetails',
                'poDetails.garmentItem',
                'country',
                'factory',
                'buyer',
                'order.productDepartment',
                'order.dealingMerchant',
                'order.dealingMerchant.team',
            )
            ->where('order_status','Confirm')
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($dealingMerchantId, function ($query) use ($dealingMerchantId) {
                $query->whereHas('order', function ($query) use ($dealingMerchantId) {
                    return $query->where('dealing_merchant_id', $dealingMerchantId);
                });
            })
            ->when($jobNo && $jobNo != "null", function ($query) use ($jobNo) {
                $query->whereHas('order', function ($query) use ($jobNo) {
                    $query->where('job_no', $jobNo);
                });
            })
            ->when($poNo && !in_array('All', $poNo), Filter::applyFilter('po_no', $poNo))
            ->when($searchType == "order_details" && $fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('ex_factory_date', [$fromDate, $toDate]);
            })
            ->when($searchType == "shipment_date" && $fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('ex_factory_date', [$fromDate, $toDate]);
            })
            ->when($searchType == "po_receive_date" && $fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('po_receive_date', [$fromDate, $toDate]);
            })
            ->when($styleName != "null" && $styleName, function ($query) use ($styleName) {
                $query->whereHas('order', function ($query) use ($styleName) {
                    $query->where('style_name', $styleName);
                });
            });

        $po = [];
        $purchase_orders->chunk(50, function ($item) use (&$po) {

            $poDetails = collect($item)->pluck('poDetails')->toArray();

            $colorsId = collect($poDetails)
                        ->flatten(1)
                        ->pluck('colors')
                        ->collapse()
                        ->unique()
                        ->values()
                        ->toArray();
            $sizesId = collect($poDetails)
                        ->flatten(1)
                        ->pluck('sizes')
                        ->collapse()
                        ->unique()
                        ->values()
                        ->toArray();

            $colors = Color::query()->whereIn('id', $colorsId)->get()->keyBy('id');
            $sizes = Size::query()->whereIn('id', $sizesId)->get()->keyBy('id');

            $po[] =  collect($item)->map(function ($po) use ($colors,$sizes) {

                return [
                    'id' => $po->id,
                    'fob' => $po->avg_rate_pc_set ?? 0,
                    'factory' => $po->factory->factory_name ?? '',
                    'buyer' => $po->buyer->name ?? '',
                    'po_no' => $po->po_no,
                    'ship_date' => $po->ex_factory_date,
                    'po_receive_date' => $po->po_receive_date,
                    'po_quantity' => $po->po_quantity,
                    'order' => $po->order ?? '',
                    'order_uom_id' => $po->order->order_uom_id ?? '',
                    'country' => $po->country->name ?? '',
                    'league' => $po->league,
                    'required_hanger' => $po->required_hanger,
                    'ex_factory_date' => $po->ex_factory_date,
                    'breakdown_data' => collect($po->poDetails)->map(function ($poDetails)  use ($colors,$sizes) {

                        $colors = collect($poDetails->colors)->map(function ($value) use ($colors) {
                            return $colors[$value];
                        });

                        $sizes = collect($poDetails->sizes)->map(function ($value) use ($sizes) {
                            return $sizes[$value];
                        });
                        return [
                            'garment_item' => $poDetails->garmentItem ? $poDetails->garmentItem->name : '',
                            'colors' => $poDetails->colors ? $colors : [],
                            'sizes' => $poDetails->sizes ? $sizes : [],
                            'quantity_matrix' => $poDetails->quantity_matrix,
                            'particulars' => PurchaseOrder::particulars,
                        ];
                    }),
                ];
            })->toArray();
        });

        $data['pos'] = collect($po)->flatten(1);
        $data['team'] = MerchandisingVariableSettings::query()
                ->where("factory_id", $factoryId)
                ->first()['variables_details']['team_maintain'] ?? 2;

        return $data;
    }

    public static function requestToData(Request $request): array
    {
        $data['factoryId'] = $request->get('factory_id') ?? null;
        $data['buyerId'] = $request->get('buyer_id') ?? null;
        $data['dealingMerchantId'] = $request->get('dealing_merchant_id') ?? null;
        $data['jobNo'] = $request->get('job_no') ?? null;
        $data['poNo'] = $request->get('po_no') ?? [];
        $data['fromDate'] = $request->get('from_date') ?? null;
        $data['toDate'] = $request->get('to_date') ?? null;
        $data['searchType'] = $request->get('search_type') ?? null;

        return $data;
    }

    public static function colorWiseReport(OrderReportDTO $orderReportDTO)
    {
        $factoryId = $orderReportDTO->getFactoryId();
        $buyerId = $orderReportDTO->getBuyerId();
        $jobNo = $orderReportDTO->getJobNo();
        $poNo = $orderReportDTO->getPoNo();
        $styleName = $orderReportDTO->getStyleName();
        $searchType = $orderReportDTO->getSearchType() ?? 'shipment_date'; //if search type is null then it'll filter by shipment_date
        $fromDate = $orderReportDTO->getFromDate() ? Carbon::parse($orderReportDTO->getFromDate())->format('Y-m-d') : null;
        $toDate = Carbon::parse($orderReportDTO->getToDate())->format('Y-m-d');

        return Order::query()
            ->with(['buyer', 'garmentsItemGroup', 'purchaseOrders' => function ($query) use ($searchType, $fromDate, $toDate, $poNo) {
                $query->when($searchType && ($searchType == 'shipment_date') && $fromDate, function($q) use ($fromDate, $toDate) {
                    $q->where('ex_factory_date', '>=', $fromDate);
                    $q->where('ex_factory_date', '<=', $toDate);
                });
                $query->when($searchType && ($searchType == 'po_receive_date') && $fromDate, function($q) use ($fromDate, $toDate) {
                    $q->where('po_receive_date', '>=', $fromDate);
                    $q->where('po_receive_date', '<=', $toDate);
                });
                $query->when($poNo, function ($query) use ($poNo) {
                    $query->where('po_no', $poNo);
                });
            }, 'purchaseOrders.poDetails.garmentItem', 'purchaseOrders.country'])
            ->where('factory_id', $factoryId)
            ->when($buyerId, function ($query) use ($buyerId) {
                $query->where('buyer_id', $buyerId);
            })->when($jobNo && $jobNo != "null", function ($query) use ($jobNo) {
                $query->where('job_no', $jobNo);
            })->when($styleName, function ($query) use ($styleName) {
                $query->where('style_name', 'like','%'.$styleName.'%');
            })->get()->flatMap(function ($value) {
                return $value->purchaseOrders->flatMap(function ($purchaseOrderCollection) use ($value) {
                    return $purchaseOrderCollection->poDetails->flatMap(
                        function ($poCollection) use ($value, $purchaseOrderCollection) {
                            return collect($poCollection->colors)->map(
                                function ($color) use ($value, $purchaseOrderCollection, $poCollection) {
                                    $quantityPerPcs = collect($poCollection->quantity_matrix)
                                            ->where('particular', PurchaseOrder::QTY)
                                            ->where('color_id', $color)
                                            ->sum('value') ?? 0;

                                    $colorName = collect($poCollection->quantity_matrix)
                                        ->where('particular', PurchaseOrder::QTY)
                                        ->where('color_id', $color)->first();

                                    $totalValue = $purchaseOrderCollection->po_quantity * $purchaseOrderCollection->avg_rate_pc_set;

                                    return [
                                        'buyer' => $value->buyer->name,
                                        'item' => $poCollection->garmentItem->name,
                                        'style' => $value->style_name,
                                        'port' => $purchaseOrderCollection->country->name,
                                        'po' => $purchaseOrderCollection->po_no,
                                        'color' => $colorName['color'] ?? null,
                                        'quantity_per_pack' => $purchaseOrderCollection->po_quantity ?? 0,
                                        'quantity_per_pcs' => $quantityPerPcs,
                                        'fob' => $purchaseOrderCollection->avg_rate_pc_set ?? 0,
                                        'comm_file_no' => $purchaseOrderCollection->comm_file_no ?? 0,
                                        'total_value' => $totalValue,
                                        'shipment_date' => $purchaseOrderCollection->ex_factory_date ? Carbon::parse($purchaseOrderCollection->ex_factory_date)->format('d-m-Y') : null,
                                        'images' => $value->images,
                                        'ex_factory_date' => $purchaseOrderCollection->ex_factory_date,
                                        'po_receive_date' => $purchaseOrderCollection->po_receive_date,
                                    ];
                                }
                            )->filter(function ($value) {
                                return $value['quantity_per_pcs'] !== 0;
                            });
                        }
                    );
                });
            });
    }
}
