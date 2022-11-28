<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class BuyerSeasonColorOrderReportService
{
    public $buyerId, $seasonId, $shipmentType, $fromDate, $toDate;

    public function __construct(Request $request)
    {
        $this->buyerId = $request->input('buyer_id');
        $this->seasonId = $request->input('season_id');
        $this->shipmentType = $request->input('shipment_type');
        $this->fromDate = $request->input('from_date');
        $this->toDate = $request->input('to_date');
    }

    public function report(): array
    {
        $reportData['total_po_fob'] = 0;
        $reportData['total_po_qty'] = 0;
        $reportData['total_po_fob_value'] = 0;
        $reportData['season'] = optional(Season::query()->find($this->seasonId))->season_name;

        $reportData['reports'] = Order::query()
            ->whereHas('purchaseOrders')
            ->with([
                'purchaseOrders.poDetails',
                'buyer',
                'season',
                'productDepartment',
            ])
            ->when($this->buyerId != 'all', function ($query) {
                $query->where('buyer_id', $this->buyerId);
            })
            ->when($this->seasonId, function (Builder $query) {
                $query->where('season_id', $this->seasonId);
            })
            ->orderBy('season_id')
            ->get()
            ->map(function ($data) use (&$reportData) {
                return [$data['po_no'] => collect($data->purchaseOrders)->map(function ($po) use (&$reportData, $data) {
                    return $po->poDetails->flatmap(function ($poCollection) use ($po, $data, &$reportData) {
                        return collect($poCollection->colors)->map(function ($color) use ($poCollection, $po, $data, &$reportData) {

                            $quantityMatrix = collect($poCollection->quantity_matrix)
                                ->where('particular', PurchaseOrder::QTY)
                                ->where('color_id', $color);

                            $quantityPerPcs = $quantityMatrix->sum('value') ?? 0;
                            $colorName = $quantityMatrix->first();
                            $totalValue = $quantityPerPcs * $po->avg_rate_pc_set;

                            $reportData['total_po_fob'] += (double)$po->avg_rate_pc_set;
                            $reportData['total_po_qty'] += (double)$quantityPerPcs;
                            $reportData['total_po_fob_value'] += (double)$totalValue;
                            $reportData['buyer'] = $data->buyer->name;
                            $reportData['company'] = $data->factory->factory_name;
                            $reportData['buyer_id'] = $data->buyer_id;

                            $actual_shipment_date = $po->ex_factory_date ? Carbon::make($po->ex_factory_date)
                                ->toFormattedDateString() : null;

                            $factory_shipment_date = $po->country_ship_date ? Carbon::make($po->country_ship_date)
                                ->toFormattedDateString() : null;

                            $uom = ($data->order_uom_id && ((int)$data->order_uom_id < 3)) ?
                                PriceQuotation::STYLE_UOM[$data->order_uom_id] : PriceQuotation::STYLE_UOM['1'];

                            return [
                                'season' => $data->season->season_name ?? null,
                                'buyer' => $data->buyer->name,
                                'style' => $data->style_name,
                                'dealing_merchant' => $data->dealingMerchant->screen_name,
                                'image' => $data->images,
                                'product_dept' => $data->productDepartment->product_department,
                                'uom' => $uom,
                                'po' => $po->po_no,
                                'po_qty' => $quantityPerPcs,
                                'po_fob' => $po->avg_rate_pc_set,
                                'actual_ship_date' => $actual_shipment_date,
                                'factory_ship_date' => $factory_shipment_date,
                                'po_fob_value' => $totalValue,
                                'remarks' => $po->remarks,
                                'color' => $colorName['color'] ?? null,
                                'created_at' => $po->created_at ? Carbon::make($po->created_at)->format('d/m/Y H:ia') : null,
                                'created_by' => $po->createdBy->screen_name,
                            ];
                        });
                    });
                })];
            })
            ->collapse()
            ->collapse()
            ->collapse()
            ->when(($this->shipmentType == PurchaseOrder::EX_FACTORY_DATE && ($this->fromDate && $this->toDate)) , function ($collection) {
                return $collection->where('actual_ship_date', '>=', Carbon::make($this->fromDate)->toFormattedDateString())
                    ->where('actual_ship_date', '<=', Carbon::make($this->toDate)->toFormattedDateString());
            })
            ->when(($this->shipmentType == PurchaseOrder::COUNTRY_SHIPMENT_DATE && ($this->fromDate && $this->toDate)) , function ($collection) {
                return $collection->where('factory_ship_date', '>=', Carbon::make($this->fromDate)->toFormattedDateString())
                    ->where('factory_ship_date', '<=', Carbon::make($this->toDate)->toFormattedDateString());
            })->values();

        return $reportData;
    }
}
