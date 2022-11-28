<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use Carbon\Carbon;
use PDF;
use Excel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Inventory\Exports\YarnStockSummaryExport;
use SkylarkSoft\GoRMG\Inventory\Exports\YarnStockSummarySupplierLotWiseExport;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class YarnStockSummaryReport
{
    public function getData($request)
    {
        $storeId = $request->get('store_id') ?? Store::query()->first()->id;
        $store = Store::query()->find($storeId);
        $fromDate = $request->get('from_date') ?? now()->startOfYear()->toDateString();
        $previousOneYearDate = Carbon::parse($fromDate)->subDays(366)->toDateString();
        $toDate = $request->get('to_date') ?? date('Y-m-d');
        $countId = $request->get('count_id') ?? null;
        $yarnLot = $request->get('yarn_lot') ?? null;
        $yarnBrand = $request->get('yarn_brand') ?? null;
        $yarnType = $request->get('yarn_type');
        $certification = $request->get('certification');
        $origin = $request->get('origin');

        $openingStockData = YarnDateWiseStockSummary::query()
            ->with(['count', 'composition', 'type', 'uom'])
            ->where('store_id', $storeId)
            ->when($countId, Filter::applyFilter('yarn_count_id', $countId))
            ->when($yarnLot, Filter::applyFilter('yarn_lot', $yarnLot))
            ->when($yarnBrand, Filter::applyFilter('yarn_brand', $yarnBrand))
            ->when($yarnType, Filter::applyFilter('yarn_type_id', $yarnType))
            ->selectRaw('yarn_count_id, yarn_composition_id, yarn_type_id, yarn_color, yarn_brand, yarn_lot, uom_id, store_id,
                        SUM(receive_qty) as receive_qty, SUM(receive_return_qty) as receive_return_qty,
                        SUM(issue_qty) as issue_qty, SUM(issue_return_qty) as issue_return_qty,
                        SUM(receive_qty - receive_return_qty - issue_qty + issue_return_qty) as balance_qty,
                        SUM((receive_qty - receive_return_qty - issue_qty + issue_return_qty) * rate) as balance_amount')
            ->whereDate('date', '>=', $previousOneYearDate )
            ->whereDate('date', '<', $fromDate )
            ->when($certification, function ($query) use ($certification) {
                $yarnReceive = YarnReceiveDetail::query()->where('certification', $certification)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->when($origin, function ($query) use ($origin) {
                $yarnReceive = YarnReceiveDetail::query()->where('origin', $origin)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->groupBy('yarn_count_id', 'yarn_composition_id', 'yarn_type_id', 'yarn_color', 'yarn_brand', 'yarn_lot', 'uom_id')
            ->get()
            ->map(function ($reportData) use ($store) {

                $yarn = $this->item($reportData);
                $receiveBasis = $yarn->yarnReceive->receive_basis ?? null;
                $pi_no = null;
                $pi_date = null;
                if ($receiveBasis && $receiveBasis == 'pi') {
                    $pi_no = $yarn->yarnReceive->receive_basis_no ?? '';
                    $pi_date = $yarn->yarnReceive->pi->pi_receive_date ?? '';
                }

                $openingQty = $reportData->balance_qty ?? 0;
                $openingAmount = $reportData->balance_amount ?? 0;
                $closingQty = 0;

                $receiveQty = 0;
                $issueQty = 0;
                $rate = $openingQty > 0 ? round($openingAmount / $openingQty , 2) : 0;

                $particular = implode(',', [
                    $reportData->count->yarn_count,
                    $reportData->composition->yarn_composition,
                    $reportData->type->yarn_type,
                    $reportData->yarn_lot,
                    $reportData->yarn_color,
                    $reportData->yarn_lot,
                    $reportData->yarn_brand,
                ]);

                return [
                    'particular' => $particular,
                    'yarn_count' => $reportData->count->yarn_count ?? '',
                    'yarn_composition' => $reportData->composition->yarn_composition ?? '',
                    'yarn_type' => $reportData->type->name ?? '',
                    'certification' => $reportData->receiveDetails->unique('certification')->pluck('certification')->implode(', '),
                    'origin' => $reportData->receiveDetails->unique('origin')->pluck('origin')->implode(', '),
                    'yarn_count_id' => $reportData->yarn_count_id,
                    'yarn_composition_id' => $reportData->yarn_composition_id,
                    'yarn_type_id' => $reportData->yarn_type_id,
                    'yarn_color' => $reportData->yarn_color,
                    'yarn_brand' => $reportData->yarn_brand,
                    'yarn_lot' => $reportData->yarn_lot,
                    'supplier' => $yarn->supplier->name ?? '',
                    'uom_id' => $reportData->uom_id,
                    'lot' => $reportData->yarn_lot,
                    'uom' => $reportData->uom->unit_of_measurement,
                    'pi_no' => $pi_no ,
                    'pi_date' => $pi_date,
                    'lc_no' => $yarn->yarnReceive->lc_no ?? '',
                    'lc_date' => $yarn->yarnReceive->lc_receive_date ?? '',
                    'opening' => [
                        'qty' => $openingQty,
                        'rate' => $rate,
                        'value' => $openingAmount,
                    ],
                    'receive' => [
                        'qty' => $receiveQty,
                        'rate' => number_format($rate, 2),
                        'value' => number_format($receiveQty * $rate, 2),
                    ],
                    'issue' => [
                        'qty' => $issueQty,
                        'rate' => number_format($rate, 2),
                        'value' => number_format($issueQty * $rate, 2),
                    ],
                    'closing' => [
                        'qty' => $closingQty,
                        'rate' => number_format($rate, 2),
                        'value' => number_format($closingQty * $rate, 2),
                    ],
                    'storage' => $store->name,
                    'age' => $yarn ? Carbon::now()->diffInDays(optional($yarn->yarnReceive)->receive_date) : null,

                ];
            });

        $stockData = YarnDateWiseStockSummary::query()
            ->with(['count', 'composition', 'type', 'uom'])
            ->where('store_id', $storeId)
            ->when($countId, Filter::applyFilter('yarn_count_id', $countId))
            ->when($yarnLot, Filter::applyFilter('yarn_lot', $yarnLot))
            ->when($yarnBrand, Filter::applyFilter('yarn_brand', $yarnBrand))
            ->when($yarnType, Filter::applyFilter('yarn_type_id', $yarnType))
            ->selectRaw('yarn_count_id, yarn_composition_id, yarn_type_id, yarn_color, yarn_brand, yarn_lot, uom_id, store_id,
                        SUM(receive_qty) as receive_qty, SUM(receive_return_qty) as receive_return_qty,
                        SUM(issue_qty) as issue_qty, SUM(issue_return_qty) as issue_return_qty,
                        SUM(receive_qty - receive_return_qty - issue_qty + issue_return_qty) as balance_qty,
                        SUM((receive_qty - receive_return_qty - issue_qty + issue_return_qty) * rate) as balance_amount')
            ->whereDate('date', '>=', $fromDate )
            ->whereDate('date', '<=', $toDate )
            ->when($certification, function ($query) use ($certification) {
                $yarnReceive = YarnReceiveDetail::query()->where('certification', $certification)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->when($origin, function ($query) use ($origin) {
                $yarnReceive = YarnReceiveDetail::query()->where('origin', $origin)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->groupBy('yarn_count_id', 'yarn_composition_id', 'yarn_type_id', 'yarn_color', 'yarn_brand', 'yarn_lot', 'uom_id')
            ->get()
            ->map(function ($reportData) use ($store, $fromDate, $storeId, $openingStockData) {
                $openingQuery = collect($openingStockData)
                    ->where('yarn_count_id', $reportData->yarn_count_id)
                    ->where('yarn_composition_id', $reportData->yarn_composition_id)
                    ->where('yarn_type_id', $reportData->yarn_type_id)
                    ->where('yarn_color', $reportData->yarn_color)
                    ->where('yarn_lot', $reportData->yarn_lot)
                    ->where('uom_id', $reportData->uom_id)
                    ->where('yarn_brand', $reportData->yarn_brand)
                    ->first();
                //$openingDataExists = $openingQuery && $openingQuery->count();
                $openingQty = $openingQuery ? $openingQuery['opening']['qty'] : 0;
                $openingAmount = $openingQuery ? $openingQuery['opening']['value'] : 0;
                $openingRate = $openingQuery ? $openingQuery['opening']['rate'] : 0;

                $crrentBalance = $reportData->balance_qty ?? 0;
                $crrentAmount = $reportData->balance_amount ?? 0;
                $rate = $crrentBalance > 0 ? round($crrentAmount / $crrentBalance , 2) : 0;
                $closingQty = $openingQty + $crrentBalance;
                $closingAmount = $openingAmount + $crrentAmount;
                $closingRate = $closingQty > 0 ? round($closingAmount / $closingQty , 2) : 0;

                $receiveQty = $reportData->receive_qty;
                $issueQty = $reportData->issue_qty;

                $particular = implode(',', [
                    $reportData->count->yarn_count,
                    $reportData->composition->yarn_composition,
                    $reportData->type->yarn_type,
                    $reportData->yarn_lot,
                    $reportData->yarn_color,
                    $reportData->yarn_lot,
                    $reportData->yarn_brand,
                ]);

                $yarn = $this->item($reportData);
                $receiveBasis = $yarn->yarnReceive->receive_basis ?? null;
                $pi_no = null;
                $pi_date = null;
                if ($receiveBasis && $receiveBasis == 'pi') {
                    $pi_no = $yarn->yarnReceive->receive_basis_no ?? '';
                    $pi_date = $yarn->yarnReceive->pi->pi_receive_date ?? '';
                }

                return [
                    'particular' => $particular,
                    'yarn_count' => $reportData->count->yarn_count ?? '',
                    'yarn_composition' => $reportData->composition->yarn_composition ?? '',
                    'yarn_type' => $reportData->type->name ?? '',
                    'certification' => $reportData->receiveDetails->unique('certification')->pluck('certification')->implode(', '),
                    'origin' => $reportData->receiveDetails->unique('origin')->pluck('origin')->implode(', '),
                    'yarn_count_id' => $reportData->yarn_count_id,
                    'yarn_composition_id' => $reportData->yarn_composition_id,
                    'yarn_type_id' => $reportData->yarn_type_id,
                    'yarn_color' => $reportData->yarn_color,
                    'yarn_brand' => $reportData->yarn_brand,
                    'yarn_lot' => $reportData->yarn_lot,
                    'uom_id' => $reportData->uom_id,
                    'supplier' => $yarn->supplier->name ?? '',
                    'lot' => $reportData->yarn_lot,
                    'uom' => $reportData->uom->unit_of_measurement,
                    'pi_no' => $pi_no ,
                    'pi_date' => $pi_date,
                    'lc_no' => $yarn->yarnReceive->lc_no ?? '',
                    'lc_date' => $yarn->yarnReceive->lc_receive_date ?? '',
                    'opening' => [
                        'qty' => $openingQty,
                        'rate' => number_format($openingRate, 2),
                        'value' => number_format($openingAmount, 2),
                    ],
                    'receive' => [
                        'qty' => $receiveQty,
                        'rate' => number_format($rate, 2),
                        'value' => number_format($receiveQty * $rate, 2),
                    ],
                    'issue' => [
                        'qty' => $issueQty,
                        'rate' => number_format($rate, 2),
                        'value' => number_format($issueQty * $rate, 2),
                    ],
                    'closing' => [
                        'qty' => $closingQty,
                        'rate' => number_format($closingRate, 2),
                        'value' => number_format($closingAmount, 2),
                    ],
                    'storage' => $store->name,
                    'age' => $yarn ? Carbon::now()->diffInDays(optional($yarn->yarnReceive)->receive_date) : null,

                ];
            });
        $data = $stockData && count($stockData) ? $stockData : [];
        $key = $stockData && count($stockData) ? count($stockData) - 1 : -1;
        foreach($openingStockData as $openingStock) {
            $existingData = $stockData && count($stockData) &&
                collect($stockData)->where('yarn_count_id', $openingStock['yarn_count_id'])
                    ->where('yarn_composition_id', $openingStock['yarn_composition_id'])
                    ->where('yarn_type_id', $openingStock['yarn_type_id'])
                    ->where('yarn_color', $openingStock['yarn_color'])
                    ->where('yarn_lot', $openingStock['yarn_lot'])
                    ->where('uom_id', $openingStock['uom_id'])
                    ->where('yarn_brand', $openingStock['yarn_brand'])
                    ->count();
            if ($existingData) {
                continue;
            }
            $data[++$key] = [
                'particular' => $openingStock['particular'],
                'yarn_count' => $openingStock['yarn_count'] ?? '',
                'yarn_composition' => $openingStock['yarn_composition'] ?? '',
                'yarn_type' => $openingStock['yarn_type'] ?? '',
                'certification' => $openingStock['certification'],
                'origin' => $openingStock['origin'],
                'yarn_count_id' => $openingStock['yarn_count_id'],
                'yarn_composition_id' => $openingStock['yarn_composition_id'],
                'yarn_type_id' => $openingStock['yarn_type_id'],
                'yarn_color' => $openingStock['yarn_color'],
                'yarn_brand' => $openingStock['yarn_brand'],
                'yarn_lot' => $openingStock['yarn_lot'],
                'supplier' => $openingStock['supplier'],
                'uom_id' => $openingStock['uom_id'],
                'lot' => $openingStock['yarn_lot'],
                'uom' => $openingStock['uom'],
                'pi_no' => $openingStock['pi_no'],
                'pi_date' => $openingStock['pi_date'],
                'lc_no' => $openingStock['lc_no'],
                'lc_date' => $openingStock['lc_date'],
                'opening' => [
                    'qty' => $openingStock['opening']['qty'],
                    'rate' => number_format($openingStock['opening']['rate'], 2),
                    'value' => number_format($openingStock['opening']['value'], 2),
                ],
                'receive' => $openingStock['receive'],
                'issue' => $openingStock['issue'],
                'closing' => [
                    'qty' => $openingStock['opening']['qty'],
                    'rate' => number_format($openingStock['opening']['rate'], 2),
                    'value' => number_format($openingStock['opening']['value'], 2),
                ],
                'storage' => $openingStock['storage'],
                'age' => $openingStock['age']
            ];
        }
        return $data;
    }

    public function getStockSummaryData($request)
    {
        $storeId = $request->get('store_id') ?? Store::query()->first()->id;
        $store = Store::query()->find($storeId);
        $countId = $request->get('count_id') ?? null;
        $yarnLot = $request->get('yarn_lot') ?? null;
        $yarnBrand = $request->get('yarn_brand') ?? null;
        $compositionId = $request->get('composition_id') ?? null;
        $productCode = $request->get('product_code') ?? null;
        $fromDate = $request->get('from_date') ?? now()->startOfYear()->toDateString();
        $previousOneYearDate = Carbon::parse($fromDate)->subDays(366)->toDateString();
        $toDate = $request->get('to_date') ?? date('Y-m-d');
        $yarnType = $request->get('yarn_type');
        $certification = $request->get('certification');
        $origin = $request->get('origin');

        $openingStockData = YarnDateWiseStockSummary::query()
            ->with(['count', 'composition', 'type', 'uom', 'receiveDetails'])
            ->where('store_id', $storeId)
            ->when($yarnLot, Filter::applyFilter('yarn_lot', $yarnLot))
            ->when($yarnBrand, Filter::applyFilter('yarn_brand', $yarnBrand))
            ->selectRaw('yarn_count_id, yarn_composition_id, yarn_type_id, yarn_color, yarn_brand, yarn_lot, uom_id,
                        SUM(receive_qty) as receive_qty, SUM(receive_return_qty) as receive_return_qty,
                        SUM(issue_qty) as issue_qty, SUM(issue_return_qty) as issue_return_qty,
                        SUM(receive_qty - receive_return_qty - issue_qty + issue_return_qty) as balance_qty,
                        SUM((receive_qty - receive_return_qty - issue_qty + issue_return_qty) * rate) as balance_amount')
            ->whereDate('date', '>=', $previousOneYearDate )
            ->whereDate('date', '<', $fromDate )
            ->when($compositionId, Filter::applyFilter('yarn_composition_id', $compositionId))
            ->when($countId, Filter::applyFilter('yarn_count_id', $countId))
            ->when($yarnType, Filter::applyFilter('yarn_type_id', $yarnType))
            ->when($productCode, function ($query) use ($productCode) {
                $yarnReceive = YarnReceiveDetail::query()->where('product_code', $productCode)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->when($certification, function ($query) use ($certification) {
                $yarnReceive = YarnReceiveDetail::query()->where('certification', $certification)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->when($origin, function ($query) use ($origin) {
                $yarnReceive = YarnReceiveDetail::query()->where('origin', $origin)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->groupBy('yarn_count_id', 'yarn_composition_id', 'yarn_type_id', 'yarn_color', 'yarn_brand', 'yarn_lot', 'uom_id')
            ->get()
            ->map(function ($reportData) use ($store) {

                $openingQty = $reportData->balance_qty ?? 0;
                $openingAmount = $reportData->balance_amount ?? 0;
                $closingQty = 0;

                $receiveQty = 0;
                $receiveReturnQty = 0;
                $issueQty = 0;
                $issueReturnQty = 0;
                $rate = $openingQty > 0 ? round($openingAmount / $openingQty , 2) : 0;

                $particular = implode(',', [
                    $reportData->count->yarn_count,
                    $reportData->composition->yarn_composition,
                    $reportData->type->yarn_type,
                    $reportData->yarn_lot,
                    $reportData->yarn_color,
                    $reportData->yarn_lot,
                    $reportData->yarn_brand,
                ]);

                return [
                    'particular' => $particular,
                    'yarn_count' => $reportData->count->yarn_count ?? '',
                    'yarn_composition' => $reportData->composition->yarn_composition ?? '',
                    'yarn_type' => $reportData->type->name ?? '',
                    'certification' => $reportData->receiveDetails->unique('certification')->pluck('certification')->implode(', '),
                    'origin' => $reportData->receiveDetails->unique('origin')->pluck('origin')->implode(', '),
                    'yarn_count_id' => $reportData->yarn_count_id,
                    'yarn_composition_id' => $reportData->yarn_composition_id,
                    'yarn_type_id' => $reportData->yarn_type_id,
                    'yarn_color' => $reportData->yarn_color,
                    'yarn_brand' => $reportData->yarn_brand,
                    'yarn_lot' => $reportData->yarn_lot,
                    'uom_id' => $reportData->uom_id,
                    'lot' => $reportData->yarn_lot,
                    'uom' => $reportData->uom->unit_of_measurement,
                    'pi_no' => '',
                    'pi_date' => '',
                    'lc_no' => '',
                    'lc_date' => '',
                    'opening' => [
                        'qty' => numberFormat($openingQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($openingAmount),
                    ],
                    'receive' => [
                        'qty' => numberFormat($receiveQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($receiveQty * $rate),
                    ],
                    'receive_return' => [
                        'qty' => numberFormat($receiveReturnQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($receiveReturnQty * $rate),
                    ],
                    'issue' => [
                        'qty' => numberFormat($issueQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($issueQty * $rate),
                    ],
                    'issue_return' => [
                        'qty' => numberFormat($issueReturnQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($issueReturnQty * $rate),
                    ],
                    'closing' => [
                        'qty' => numberFormat($closingQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($closingQty * $rate),
                    ],
                    'storage' => $store->name,
                    'age' => null
                ];
            });


        /*
         * In this query give wrong calculation for balance_amount,
         * that's why we do the calculation in 441 no Line.
         */
        $stockData = YarnDateWiseStockSummary::query()
            ->with(['count', 'composition', 'type', 'uom', 'receiveDetails'])
            ->where('store_id', $storeId)
            ->when($yarnLot, Filter::applyFilter('yarn_lot', $yarnLot))
            ->when($yarnBrand, Filter::applyFilter('yarn_brand', $yarnBrand))
            ->selectRaw('yarn_count_id, yarn_composition_id, yarn_type_id, yarn_color, yarn_brand, yarn_lot, uom_id,
                        SUM(receive_qty) as receive_qty, SUM(receive_return_qty) as receive_return_qty,
                        SUM(issue_qty) as issue_qty, SUM(issue_return_qty) as issue_return_qty,
                        SUM(rate) as rate_total, COUNT(rate) as rate_count,
                        SUM(CAST(receive_qty as decimal(12, 4)) - CAST(receive_return_qty as decimal(12, 4)) - CAST(issue_qty as decimal(12, 4)) + CAST(issue_return_qty as decimal(12, 4))) as balance_qty,
                        SUM((CAST(receive_qty as decimal(12, 4)) - CAST(receive_return_qty as decimal(12, 4)) - CAST(issue_qty as decimal(12, 4)) + CAST(issue_return_qty as decimal(12, 4))) * CAST(rate as decimal(12, 4))) as balance_amount')
            ->whereDate('date', '>=', $fromDate )
            ->whereDate('date', '<=', $toDate )
            ->when($compositionId, Filter::applyFilter('yarn_composition_id', $compositionId))
            ->when($countId, Filter::applyFilter('yarn_count_id', $countId))
            ->when($yarnType, Filter::applyFilter('yarn_type_id', $yarnType))
            ->when($productCode, function ($query) use ($productCode) {
                $yarnReceive = YarnReceiveDetail::query()->where('product_code', $productCode)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->when($certification, function ($query) use ($certification) {
                $yarnReceive = YarnReceiveDetail::query()->where('certification', $certification)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->when($origin, function ($query) use ($origin) {
                $yarnReceive = YarnReceiveDetail::query()->where('origin', $origin)->get();
                $query->whereIn('yarn_count_id', $this->getId($yarnReceive, 'yarn_count_id'));
                $query->whereIn('yarn_composition_id', $this->getId($yarnReceive, 'yarn_composition_id'));
                $query->whereIn('yarn_type_id', $this->getId($yarnReceive, 'yarn_type_id'));
                $query->whereIn('yarn_color', $this->getId($yarnReceive, 'yarn_color'));
                $query->whereIn('yarn_brand', $this->getId($yarnReceive, 'yarn_brand'));
                $query->whereIn('yarn_lot', $this->getId($yarnReceive, 'yarn_lot'));
                $query->whereIn('uom_id', $this->getId($yarnReceive, 'uom_id'));
            })
            ->groupBy('yarn_count_id', 'yarn_composition_id', 'yarn_type_id', 'yarn_color', 'yarn_brand', 'yarn_lot', 'uom_id')
            ->get()
            ->map(function ($reportData) use ($store, $fromDate, $storeId, $openingStockData) {
                $openingQuery = collect($openingStockData)
                    ->where('yarn_count_id', $reportData->yarn_count_id)
                    ->where('yarn_composition_id', $reportData->yarn_composition_id)
                    ->where('yarn_type_id', $reportData->yarn_type_id)
                    ->where('yarn_color', $reportData->yarn_color)
                    ->where('yarn_lot', $reportData->yarn_lot)
                    ->where('uom_id', $reportData->uom_id)
                    ->where('yarn_brand', $reportData->yarn_brand)
                    ->first();
                //$openingDataExists = $openingQuery && $openingQuery->count();
                $openingQty = $openingQuery ? $openingQuery['opening']['qty'] : 0;
                $openingAmount = $openingQuery ? $openingQuery['opening']['value'] : 0;
                $openingRate = $openingQuery ? $openingQuery['opening']['rate'] : 0;

                $rate = $reportData->rate_total / $reportData->rate_count;
                $reportData->balance_amount = ($reportData->receive_qty - $reportData->receive_return_qty - $reportData->issue_qty + $reportData->issue_return_qty) * $rate;

                $crrentBalance = $reportData->balance_qty ?? 0;
                $crrentAmount = $reportData->balance_amount ?? 0;
                $rate = $crrentBalance > 0 ? round($crrentAmount / $crrentBalance , 2) : 0;
                $closingQty = $openingQty + $crrentBalance;
                $closingAmount = $openingAmount + $crrentAmount;
                $closingRate = $closingQty > 0 ? round($closingAmount / $closingQty , 2) : 0;

                $receiveQty = $reportData->receive_qty;
                $receiveReturnQty = $reportData->receive_return_qty;
                $issueQty = $reportData->issue_qty;
                $issueReturnQty = $reportData->issue_return_qty;

                $particular = implode(',', [
                    $reportData->count->yarn_count,
                    $reportData->composition->yarn_composition,
                    $reportData->type->yarn_type,
                    $reportData->yarn_lot,
                    $reportData->yarn_color,
                    $reportData->yarn_lot,
                    $reportData->yarn_brand,
                ]);

                return [
                    'particular' => $particular,
                    'yarn_count' => $reportData->count->yarn_count ?? '',
                    'yarn_composition' => $reportData->composition->yarn_composition ?? '',
                    'certification' => $reportData->receiveDetails->unique('certification')->pluck('certification')->implode(', '),
                    'origin' => $reportData->receiveDetails->unique('origin')->pluck('origin')->implode(', '),
                    'yarn_type' => $reportData->type->name ?? '',
                    'yarn_count_id' => $reportData->yarn_count_id,
                    'yarn_composition_id' => $reportData->yarn_composition_id,
                    'yarn_type_id' => $reportData->yarn_type_id,
                    'yarn_color' => $reportData->yarn_color,
                    'yarn_brand' => $reportData->yarn_brand,
                    'yarn_lot' => $reportData->yarn_lot,
                    'uom_id' => $reportData->uom_id,
                    'lot' => $reportData->yarn_lot,
                    'uom' => $reportData->uom->unit_of_measurement,
                    'pi_no' => '',
                    'pi_date' => '',
                    'lc_no' => '',
                    'lc_date' => '',
                    'opening' => [
                        'qty' => numberFormat($openingQty),
                        'rate' => numberFormat($openingRate),
                        'value' => numberFormat($openingAmount),
                    ],
                    'receive' => [
                        'qty' => numberFormat($receiveQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($receiveQty * $rate),
                    ],
                    'receive_return' => [
                        'qty' => numberFormat($receiveReturnQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($receiveReturnQty * $rate),
                    ],
                    'issue' => [
                        'qty' => numberFormat($issueQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($issueQty * $rate),
                    ],
                    'issue_return' => [
                        'qty' => numberFormat($issueReturnQty),
                        'rate' => numberFormat($rate),
                        'value' => numberFormat($issueReturnQty * $rate),
                    ],
                    'closing' => [
                        'qty' => numberFormat($closingQty),
                        'rate' => numberFormat($closingRate),
                        'value' => numberFormat($closingAmount),
                    ],
                    'storage' => $store->name,
                    'age' => null
                ];
            });


//        dd($stockData);

        $data = $stockData && count($stockData) ? $stockData : [];
        $key = $stockData && count($stockData) ? count($stockData) - 1 : -1;
        foreach($openingStockData as $openingStock) {
            $existingData = $stockData && count($stockData) &&
                collect($stockData)->where('yarn_count_id', $openingStock['yarn_count_id'])
                    ->where('yarn_composition_id', $openingStock['yarn_composition_id'])
                    ->where('yarn_type_id', $openingStock['yarn_type_id'])
                    ->where('yarn_color', $openingStock['yarn_color'])
                    ->where('yarn_lot', $openingStock['yarn_lot'])
                    ->where('uom_id', $openingStock['uom_id'])
                    ->where('yarn_brand', $openingStock['yarn_brand'])
                    ->count();
            if ($existingData) {
                continue;
            }
            $data[++$key] = [
                'particular' => $openingStock['particular'],
                'yarn_count' => $openingStock['yarn_count'] ?? '',
                'yarn_composition' => $openingStock['yarn_composition'] ?? '',
                'yarn_type' => $openingStock['yarn_type'] ?? '',
                'yarn_count_id' => $openingStock['yarn_count_id'],
                'yarn_composition_id' => $openingStock['yarn_composition_id'],
                'yarn_type_id' => $openingStock['yarn_type_id'],
                'certification' => $openingStock['certification'],
                'origin' => $openingStock['origin'],
                'yarn_color' => $openingStock['yarn_color'],
                'yarn_brand' => $openingStock['yarn_brand'],
                'yarn_lot' => $openingStock['yarn_lot'],
                'uom_id' => $openingStock['uom_id'],
                'lot' => $openingStock['yarn_lot'],
                'uom' => $openingStock['uom'],
                'pi_no' => $openingStock['pi_no'],
                'pi_date' => $openingStock['pi_date'],
                'lc_no' => $openingStock['lc_no'],
                'lc_date' => $openingStock['lc_date'],
                'opening' => [
                    'qty' => numberFormat($openingStock['opening']['qty']),
                    'rate' => numberFormat($openingStock['opening']['rate']),
                    'value' => numberFormat($openingStock['opening']['value']),
                ],
                'receive' => $openingStock['receive'],
                'receive_return' => $openingStock['receive_return'],
                'issue' => $openingStock['issue'],
                'issue_return' => $openingStock['issue_return'],
                'closing' => [
                    'qty' => numberFormat($openingStock['opening']['qty']),
                    'rate' => numberFormat($openingStock['opening']['rate']),
                    'value' => numberFormat($openingStock['opening']['value']),
                ],
                'storage' => $openingStock['storage'],
                'age' => $openingStock['age']
            ];
        }
        return $data;
    }


    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function yarnStockSummaryReport(Request $request)
    {
        $itemId = Item::query()
            ->where('item_name', 'LIKE', '%Yarn%')
            ->first()->id;

        $stores = Store::query()
            ->where('item_category_id', $itemId)
            ->orderByDesc('id')
            ->get();

        $yarnTypes = CompositionType::query()->get(['id', 'name']);

        $counts = YarnCount::query()->get();
        $compositions = YarnComposition::query()->get();
        $reportData = $this->getStockSummaryData($request);
        $fromDate = $request->get('from_date') ?? now()->startOfYear()->toDateString();
        $fromDate = Carbon::parse($fromDate)->subDays(365)->toDateString();
        $toDate = $request->get('to_date') ?? date('Y-m-d');

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::loadView('inventory::yarns.reports.yarn-stock-summary.pdf', compact('reportData'))
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('yarn-stock-summary.pdf');
        }

        if ($request->get('type') == 'excel') {
            return Excel::download(new YarnStockSummaryExport($reportData), 'yarn-stock-summary.xlsx');
        }

        return view('inventory::yarns.reports.yarn-stock-summary.index',
            compact('reportData', 'stores', 'counts', 'compositions', 'fromDate', 'toDate','yarnTypes'));
    }

    private function item($yarn)
    {
        return YarnReceiveDetail::query()
            ->with(['supplier:id,name', 'yarnReceive.pi:id,pi_no,pi_receive_date'])
            ->where(YarnItemAction::itemCriteria($yarn))
            ->first();
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function yarnStockSummarySupplierLotReport(Request $request)
    {
        $itemId = Item::query()
            ->where('item_name', 'LIKE', '%Yarn%')
            ->first()->id;

        $stores = Store::query()
            ->where('item_category_id', $itemId)
            ->orderByDesc('id')
            ->get();
        $counts = YarnCount::query()->get();
        $yarnTypes = CompositionType::query()->get(['id', 'name']);

        $reportData = $this->getData($request);

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::loadView('inventory::yarns.reports.yarn-stock-summary-supplier-lot-wise.pdf', compact('reportData'))
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('yarn-stock-summary-supplier-lot-wise.pdf');
        }

        if ($request->get('type') == 'excel') {
            return Excel::download(new YarnStockSummarySupplierLotWiseExport($reportData), 'daily_yarn_receive_statement.xlsx');
        }

        return view('inventory::yarns.reports.yarn-stock-summary-supplier-lot-wise.index', compact('reportData', 'stores', 'counts', 'yarnTypes'));
    }

    public function yarnStockItemWiseList(Request $request)
    {
        $q = $request->get('search');
        $stocks = YarnStockSummary::query()->latest()
            ->when($q, Filter::applyFilter('yarn_lot', $q))
            ->paginate();
        return view('inventory::yarns.reports.yarn-stock-item-wise-list', compact('stocks'));
    }

    public function getId($yarn, $column)
    {
        return $yarn->pluck($column)->unique()->values()->toArray();
    }
}
