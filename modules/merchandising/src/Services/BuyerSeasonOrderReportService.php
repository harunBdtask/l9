<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class BuyerSeasonOrderReportService
{
    public $buyerId, $seasonId, $searchType, $fromDate, $toDate;

    public function __construct(Request $request)
    {
        $this->buyerId = $request->input('buyer_id');
        $this->seasonId = $request->input('season_id');
        $this->searchType = $request->input('search_type');
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
            ->with([
                'purchaseOrders.poDetails',
                'buyer',
                'season',
                'productDepartment'
            ])
            ->whereHas('purchaseOrders', function ($query) {
                $query->when($this->searchType == 'po_receive_date' && $this->fromDate && $this->toDate,
                    Filter::applyBetweenFilter('po_receive_date', [$this->fromDate, $this->toDate]));
                $query->when($this->searchType == 'shipment_date' && $this->fromDate && $this->toDate,
                    Filter::applyBetweenFilter('country_ship_date', [$this->fromDate, $this->toDate]));
            })
            ->when($this->buyerId != 'all', function ($query) {
                $query->where('buyer_id', $this->buyerId);
            })
            ->when($this->seasonId, function (Builder $query) {
                $query->where('season_id', $this->seasonId);
            })
            ->orderBy('season_id')
            ->get()
            ->map(function ($data) use (&$reportData) {
                return [$data['po_no'] => collect($data->purchaseOrders)
                    ->map(function ($po) use (&$reportData, $data) {

                        $poValue = (double)$po->po_quantity * (double)$po->avg_rate_pc_set;
                        $reportData['total_po_fob'] += (double)$po->avg_rate_pc_set;
                        $reportData['total_po_qty'] += (double)$po->po_quantity;
                        $reportData['total_po_fob_value'] += (double)$poValue;
                        $reportData['buyer'] = $data->buyer->name;
                        $reportData['company'] = $data->factory->factory_name;
                        $reportData['buyer_id'] = $data->buyer_id;

                        return [
                            'season' => $data->season->season_name ?? null,
                            'buyer' => $data->buyer->name,
                            'style' => $data->style_name,
                            'dealing_merchant' => $data->dealingMerchant->screen_name,
                            'image' => $data->images,
                            'product_dept' => $data->productDepartment->product_department,
                            'uom' => $data->order_uom_id && (int)$data->order_uom_id < 3 ?
                                PriceQuotation::STYLE_UOM[$data->order_uom_id] : PriceQuotation::STYLE_UOM['1'],
                            'po' => $po->po_no,
                            'po_qty' => $po->po_quantity,
                            'po_fob' => $po->avg_rate_pc_set,
                            'actual_ship_date' => $po->ex_factory_date ? Carbon::make($po->ex_factory_date)
                                ->toFormattedDateString() : null,
                            'factory_ship_date' => $po->country_ship_date ? Carbon::make($po->country_ship_date)
                                ->toFormattedDateString() : null,
                            'po_receive_date' => $po->po_receive_date ? Carbon::make($po->po_receive_date)
                                ->toFormattedDateString() : null,
                            'po_fob_value' => $poValue,
                            'remarks' => $po->remarks,
                            'created_at' => $po->created_at ? Carbon::make($po->created_at)->format('d/m/Y H:ia') : null,
                            'created_by' => $po->createdBy->screen_name,
                        ];
                    })];
            })->collapse()->collapse();
        return $reportData;
    }
}
