<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\ColorSizeSummaryReport;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class ColorSizeSummaryReportService
{
    const QTY_ROWS = [
        'order_qty' => "ORDER QTY",
        'total_cutting' => "CUTTING QTY",
        'total_cutting_rejection' => "CUT REJECT",
        'total_input' => "INPUT QTY",
        'total_sewing_output' => "OUTPUT QTY",
        'out_balance' => "OUT BALANCE",
        'total_sewing_rejection' => "REJECT",
        'reject_percent' => "REJECT %",
    ];

    protected $colorSizeSummaryReport;

    public static function getWeeks(): array
    {
        $weeks = [];
        for ($i = 1; $i <= 52; $i++) {
            $weeks[$i] = 'WK-' . $i;
        }
        return $weeks;
    }

    public function make($bundle): ColorSizeSummaryReportService
    {
        $this->colorSizeSummaryReport = ColorSizeSummaryReport::where([
            'purchase_order_id' => $bundle->purchase_order_id,
            'color_id' => $bundle->color_id,
            'size_id' => $bundle->size_id
        ])->first();

        if (!$this->colorSizeSummaryReport) {
            $this->colorSizeSummaryReport = new ColorSizeSummaryReport();
            $this->colorSizeSummaryReport->buyer_id = $bundle->buyer_id;
            $this->colorSizeSummaryReport->order_id = $bundle->order_id;
            $this->colorSizeSummaryReport->purchase_order_id = $bundle->purchase_order_id;
            $this->colorSizeSummaryReport->color_id = $bundle->color_id;
            $this->colorSizeSummaryReport->size_id = $bundle->size_id;
            $this->colorSizeSummaryReport->save();
        }

        return $this;
    }

    public function cuttingProduction(BundleCard $bundleCard): ColorSizeSummaryReportService
    {
        $originalTotalRejection = $bundleCard->getOriginal('total_rejection');
        if ($bundleCard->isDirty('status') && $bundleCard->status == 1) {
            $this->colorSizeSummaryReport->total_cutting += $bundleCard->quantity;
        }
        if ($bundleCard->isDirty('total_rejection')) {
            $this->colorSizeSummaryReport->total_cutting -= $originalTotalRejection;
            $this->cuttingRejection($bundleCard->total_rejection);
        }
        return $this;
    }

    public function cuttingRejection($rejectQty): ColorSizeSummaryReportService
    {
        $this->colorSizeSummaryReport->total_cutting_rejection += $rejectQty;
        return $this;
    }

    public function inputProduction($qty): ColorSizeSummaryReportService
    {
        $this->colorSizeSummaryReport->total_input += $qty;
        $this->colorSizeSummaryReport->save();
        return $this;
    }

    public function inputChallanDelete($bundleQty): ColorSizeSummaryReportService
    {
        if ($this->colorSizeSummaryReport->total_input >= $bundleQty) {
            $this->colorSizeSummaryReport->total_input -= $bundleQty;
        } else {
            $this->colorSizeSummaryReport->total_input = 0;
        }
        return $this;
    }

    public function sewingOutput($qty): ColorSizeSummaryReportService
    {
        $this->colorSizeSummaryReport->total_sewing_output += $qty;
        return $this;
    }

    public function sewingOutputRejection($qty): ColorSizeSummaryReportService
    {

        if ($this->colorSizeSummaryReport) {
            if ($this->colorSizeSummaryReport->total_sewing_output >= $qty) {
                $this->colorSizeSummaryReport->total_sewing_output -= $qty;
            } else {
                $this->colorSizeSummaryReport->total_sewing_output = 0;
            }
            $this->colorSizeSummaryReport->total_sewing_rejection += $qty;
        }
        return $this;
    }

    public function saveOrUpdate(): bool
    {
        $this->colorSizeSummaryReport->save();
        return true;
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function report(Request $request): array
    {
        $year = $request->get('year');
        $week = $request->get('week');
        $cut_off = $request->get('cut_off');
        $buyer_id = $request->get('buyer_id');
        $order_id = $request->get('order_id');

        $date = Carbon::now();
        $date->setISODate($year, $week);
        $startOfWeek = $date->startOfWeek()->format('Y-m-d');
        $endOfWeek = $date->endOfWeek()->format('Y-m-d');

        $allPO = PurchaseOrder::query()
            ->when($buyer_id, function ($query, $buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($order_id, function ($query, $order_id) {
                $query->where('order_id', $order_id);
            })
            ->when($cut_off, function ($query, $cut_off) {
                $query->where('cut_off', $cut_off);
            })
            ->whereBetween('ex_factory_date', [$startOfWeek, $endOfWeek])
            ->pluck('id')->unique()->toArray();

        $colorSizeSummaryData = ColorSizeSummaryReport::query()
            ->with(['purchaseOrder.purchaseOrderDetails', 'order', 'size', 'color', 'buyer'])
            ->when($buyer_id, function ($query, $buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($order_id, function ($query, $order_id) {
                $query->where('order_id', $order_id);
            })
            ->whereHas('purchaseOrder')
            ->whereIn('purchase_order_id', $allPO)->get();

        $reportData['sizes'] = $sizes = $colorSizeSummaryData->unique()
            ->pluck('size.name', 'size_id');

        $reportData['reports'] = $colorSizeSummaryData
            ->groupBy(['purchase_order_id', 'color_id'])
            ->map(function ($poReports) use ($sizes) {
                return $poReports->map(function ($order) use ($sizes) {

                    $data = [];
                    $firstOrder = $order->first();
                    $firstPurchaseOrder = $firstOrder->purchaseOrder ?? null;
                    $shipDate = optional($firstPurchaseOrder)->ex_factory_date ?? null;
                    foreach (self::QTY_ROWS as $qty_key => $qty) {

                        $sizeData = self::sizeWiseReportData($sizes, $order, $qty_key);
                        $orderData = [
                            'buyer' => $firstOrder->buyer->name,
                            'style_no' => $firstOrder->order->style_name,
                            'ref_no' => $firstOrder->order->reference_no,
                            'po_no' => $firstPurchaseOrder ? $firstPurchaseOrder->po_no : null,
                            'cutt_off' => $firstPurchaseOrder ? $firstPurchaseOrder->cut_off : null,
                            'ship_date' => $shipDate ? Carbon::parse($shipDate)->format('d-M') : null,
                            'week_no' => $shipDate ? 'WK-' . Carbon::parse($shipDate)->weekOfYear : null,
                            'color' => $firstOrder->color->name,
                            'qty' => $qty,
                            'qty_key' => $qty_key,
                            'row_total' => array_sum($sizeData)
                        ];
                        $data[] = $orderData + $sizeData;

                    }

                    return $data;
                });
            });

        return $reportData;
    }

    /**
     * @param $sizes
     * @param $order
     * @param $qty_key
     * @return array
     */
    public static function sizeWiseReportData($sizes, $order, $qty_key): array
    {
        $sizeWiseReport = [];
        foreach ($sizes as $size_key => $size) {

            $orderSize = collect($order)
                ->where('size_id', $size_key)
                ->first();
            $orderSizePO = $orderSize->purchaseOrder ?? null;
            $sizeWiseQty = 0;
            $balance = ($orderSize['total_sewing_output'] ?? 0) - ($orderSize['total_input'] ?? 0);
            switch ($qty_key) {
                case 'order_qty':
                    $sizeWiseQty = $orderSizePO ? collect($orderSizePO->purchaseOrderDetails)
                        ->where('size_id', $size_key)
                        ->where('color_id', $order->first()['color_id'])
                        ->sum('quantity') : null;
                    break;

                case 'out_balance':
                    $sizeWiseQty = $balance;
                    break;

                case 'reject_percent':
                    $sizeWiseQty = isset($orderSize)
                        ? ($orderSize['total_sewing_rejection'] > 0 && $orderSize['total_input'] > 0) : false
                            ? number_format((($orderSize['total_sewing_rejection'] * 100) / $orderSize['total_input']))
                            : 0;
                    break;

                default:
                    $sizeWiseQty = $orderSize[$qty_key] ?? 0;
            }

            $sizeWiseReport[$size] = $sizeWiseQty;
        }

        return $sizeWiseReport;
    }
}
