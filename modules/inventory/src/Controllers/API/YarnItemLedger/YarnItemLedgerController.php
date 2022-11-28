<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnItemLedger;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Collection;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Exports\YarnItemLedgerReport;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssue;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueReturn;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturn;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnTransferDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class YarnItemLedgerController extends Controller
{
    public function index()
    {
        $companies = Factory::all();
        $lots = YarnStockSummary::query()
            ->pluck('yarn_lot')
            ->unique()
            ->values();
        $yarn_count = YarnCount::query()->where('factory_id', factoryId())->get();
        $yarn_composition = YarnComposition::query()->where('factory_id', factoryId())->get();
        $yarn_type = YarnType::query()->where('factory_id', factoryId())->get();
        $receivesPerPage = YarnReceiveDetail::query()->count() / 5;
        return view('inventory::yarns.reports.yarn-item-ledger.index', compact('companies', 'lots', 'yarn_count', 'yarn_composition', 'yarn_type', 'receivesPerPage'));
    }

    public function items(Request $request): JsonResponse
    {
        $count = YarnReceiveDetail::query()->count('yarn_lot') / 10;
        if (is_float($count)) {
            $count = (int)$count + 1;
        }
        $reports = $this->filterReports($request);
        return response()->json([
            'maxPage' => $count,
            'view' => (String)view('inventory::yarns.reports.yarn-item-ledger.reportTable', compact('reports'))
        ]);
    }

    public function excelReport(Request $request): BinaryFileResponse
    {
        $reports = $this->filterReports($request);
        return Excel::download(new YarnItemLedgerReport($reports), 'yarn_item_ledger_report.xlsx');
    }

    public function pdfReport(Request $request)
    {
        $reports = $this->filterReports($request);
        $pdf = PDF::loadView('inventory::yarns.reports.yarn-item-ledger.pdf', compact('reports'));
        return $pdf->stream('yarn_item_ledger_report');
    }

    private $lots = [];
    private function filterReports($request): Collection
    {
        $perPage = 10;
        $factoryId = $request->get('factory_id');
        $countId = $request->get('yarn_count');
        $compositionId = $request->get('yarn_composition');
        $typeId = $request->get('yarn_type');
        $page = (int)$request->get('page');
        $offset = 0;
        if ($page && $page >= 2) {
            $offset = ($page-1) * $perPage;
        }

        $receives = YarnReceiveDetail::query()
            ->when($request->get('yarn_lot'), function ($query) use ($request) {
                $query->whereIn('yarn_lot', $request->get('yarn_lot'));
            })
            ->when($countId, Filter::applyFilter('yarn_count_id', $countId))
            ->when($compositionId, Filter::applyFilter('yarn_composition_id', $compositionId))
            ->when($request->get('from_date') && $request->get('to_date'), function ($query) use ($request) {
                $query->whereHas('yarnReceive', function ($q) use ($request) {
                    $q->whereBetween('receive_date', [$request->get('from_date'), $request->get('to_date')]);
                });
            })
            ->when($request->get('year'), function ($query) use ($request) {
                $query->whereHas('yarnReceive', function ($q) use ($request) {
                    $q->whereYear('receive_date', $request->get('year'));
                });
            })
            ->when($request->get('factory_id'), function ($query) use ($request) {
                $query->whereHas('yarnReceive', function ($q) use ($request) {
                    $q->where('factory_id', $request->get('factory_id'));
                });
            })
            ->offset($offset)
            ->limit($perPage)
            ->get()->map(function ($detail) {
                array_push($this->lots, $detail->yarn_lot);
                $balance = (new YarnStockSummaryService)->balance($detail);
                return [
                    'supplier' => $detail->supplier->name ?? '',
                    'trans_date' => $detail->yarnReceive->receive_date ?? '',
                    'receive_no' => $detail->yarnReceive->receive_no ?? '',
                    'trans_type' => 'Receive',
                    'purpose' => $detail->yarnReceive->receive_purpose ?? '',
                    'quantity' => $detail->receive_qty,
                    'rate' => $detail->rate,
                    'lot' => $detail->yarn_lot,
                    'amount' => $detail->amount,
                    'receive_qty' => $detail->receive_qty,
                    'yarn_count' => $detail->yarn_count->yarn_count ?? '',
                    'yarn_composition' => $detail->yarn_composition->yarn_composition ?? '',
                    'yarn_type' => $detail->yarn_type->yarn_type ?? '',
                    'balance' => $balance['balance'] ?? '',
                    'balance_amount' => $balance['balance_amount'] ?? '',
                ];
            });

        $receive_returns = YarnReceiveReturn::yarnCount($countId)
            ->yarnComposition($compositionId)
            ->yarnType($typeId)
            ->with(['details' => function ($query) use ($request) {
                $query->whereIn('yarn_lot', $this->lots);
            }, 'supplier', 'details.yarn_count', 'details.composition', 'details.type'])
            ->whereHas('details', function ($query) use ($request) {
                $query->when($request->get('yarn_lot'), function ($q) use ($request) {
                    $q->whereIn('yarn_lot', $request->get('yarn_lot'));
                });
            })
            ->when($request->get('from_date') && $request->get('to_date'), function ($query) use ($request) {
                $query->whereBetween('return_date', [$request->get('from_date'), $request->get('to_date')]);
            })
            ->when($request->get('year'), function ($query) use ($request) {
                $query->whereYear('return_date', $request->get('year'));
            })
            ->when($factoryId, Filter::applyFilter('factory_id',  $factoryId))
            ->get()->map(function ($receive) {
                return $receive->details->map(function ($detail) use ($receive) {
                    $balance = (new YarnStockSummaryService)->balance($detail);
                    return [
                        'supplier' => $detail->supplier->name ?? '',
                        'trans_date' => $receive->return_date,
                        'receive_no' => $receive->receive_return_no,
                        'trans_type' => 'Receive Return',
                        'purpose' => '',
                        'quantity' => $detail->return_qty,
                        'rate' => $detail->rate,
                        'lot' => $detail->yarn_lot,
                        'amount' => $detail->return_value,
                        'yarn_count' => $detail->yarn_count->yarn_count ?? '',
                        'yarn_composition' => $detail->yarn_composition->yarn_composition ?? '',
                        'yarn_type' => $detail->yarn_type->yarn_type ?? '',
                        'balance' => $balance['balance'] ?? '',
                        'balance_amount' => $balance['balance_amount'] ?? '',
                    ];
                });
            })->flatten(1);

        $yarn_issues = YarnIssue::yarnCount($countId)
            ->yarnComposition($compositionId)
            ->yarnType($typeId)
            ->with(['details' => function ($query) use ($request) {
                $query->whereIn('yarn_lot', $this->lots);
            }, 'supplier', 'details.yarn_count', 'details.composition', 'details.type'])
            ->whereHas('details', function ($query) use ($request) {
                $query->whereIn('yarn_lot', $this->lots);
            })
            ->when($request->get('from_date') && $request->get('to_date'), function ($query) use ($request) {
                $query->whereBetween('issue_date', [$request->get('from_date'), $request->get('to_date')]);
            })
            ->when($request->get('year'), function ($query) use ($request) {
                $query->whereYear('issue_date', $request->get('year'));
            })
            ->when($factoryId, Filter::applyFilter('factory_id',  $factoryId))
            ->get()->map(function ($issue) {
                return $issue->details->map(function ($detail) use ($issue) {
                    $balance = (new YarnStockSummaryService)->balance($detail);
                    return [
                        'supplier' => $detail->supplier->name ?? '',
                        'trans_date' => $issue->issue_date,
                        'receive_no' => $issue->issue_no,
                        'trans_type' => 'Yarn Issue',
                        'purpose' => YarnIssue::ISSUE_PURPOSE[$issue->issue_purpose],
                        'quantity' => $detail->issue_qty,
                        'rate' => $detail->rate,
                        'lot' => $detail->yarn_lot,
                        'amount' => $detail->issue_value,
                        'yarn_count' => $detail->yarn_count->yarn_count ?? '',
                        'yarn_composition' => $detail->yarn_composition->yarn_composition ?? '',
                        'yarn_type' => $detail->yarn_type->yarn_type ?? '',
                        'balance' => $balance['balance'] ?? '',
                        'balance_amount' => $balance['balance_amount'] ?? '',
                    ];
                });
            })->flatten(1);

        $issue_returns = YarnIssueReturn::yarnCount($countId)
            ->yarnComposition($compositionId)
            ->yarnType($typeId)
            ->with(['details' => function ($query) use ($request) {
                $query->whereIn('yarn_lot', $this->lots);
            }, 'details.yarn_count', 'details.composition', 'details.type'])
            ->whereHas('details', function ($query) use ($request) {
                $query->whereIn('yarn_lot', $this->lots);
            })
            ->when($request->get('from_date') && $request->get('to_date'), function ($query) use ($request) {
                $query->whereBetween('return_date', [$request->get('from_date'), $request->get('to_date')]);
            })
            ->when($request->get('year'), function ($query) use ($request) {
                $query->whereYear('return_date', $request->get('year'));
            })
            ->when($factoryId, Filter::applyFilter('factory_id',  $factoryId))
            ->get()->flatMap(function ($receive) {
                return $receive->details->map(function ($detail) use ($receive) {
                    $balance = (new YarnStockSummaryService)->balance($detail);
                    return [
                        'supplier' => $detail->supplier->name ?? '',
                        'trans_date' => $receive->return_date,
                        'receive_no' => $receive->issue_return_no,
                        'trans_type' => 'Issue Return',
                        'purpose' => '',
                        'quantity' => $detail->return_qty,
                        'rate' => $detail->rate,
                        'lot' => $detail->yarn_lot,
                        'amount' => $detail->return_value,
                        'yarn_count' => $detail->yarn_count->yarn_count ?? '',
                        'yarn_composition' => $detail->yarn_composition->yarn_composition ?? '',
                        'yarn_type' => $detail->yarn_type->yarn_type ?? '',
                        'balance' => $balance['balance'] ?? '',
                        'balance_amount' => $balance['balance_amount'] ?? '',
                    ];
                });
            });

        $transfers = YarnTransferDetail::query()
            ->with('yarnTransfer', 'composition', 'yarn_count', 'type')
            ->whereIn('yarn_lot', $this->lots)
            ->when($countId, function ($query) use ($countId) {
                $query->where('yarn_count_id', $countId);
            })
            ->when($compositionId, function ($query) use ($compositionId) {
                $query->where('yarn_composition_id', $compositionId);
            })
            ->when($typeId, function ($query) use ($typeId) {
                $query->where('yarn_type_id', $typeId);
            })
            ->when($request->get('factory_id'), function ($query) use ($request) {
                $query->whereHas('yarnTransfer', function ($q) use ($request) {
                    $q->where('factory_id', $request->get('factory_id'));
                });
            })
            ->when($request->get('year'), function ($query) use ($request) {
                $query->whereHas('yarnTransfer', function ($q) use ($request) {
                    $q->whereYear('transfer_date', $request->get('year'));
                });
            })
            ->get()->map(function ($transfer) {
                $balance = (new YarnStockSummaryService)->balance($transfer);
                return [
                    'supplier' => '',
                    'trans_date' => $transfer->yarnTransfer->transfer_date ?? '',
                    'receive_no' => $transfer->yarnTransfer->transfer_no ?? '',
                    'trans_type' => 'Yarn Transfer',
                    'purpose' => '',
                    'quantity' => $transfer->transfer_qty,
                    'rate' => $transfer->rate,
                    'lot' => $transfer->yarn_lot,
                    'amount' => $transfer->transfer_qty * $transfer->rate,
                    'receive_qty' => '',
                    'yarn_count' => $transfer->yarn_count->yarn_count ?? '',
                    'yarn_composition' => $transfer->composition->yarn_composition ?? '',
                    'yarn_type' => $transfer->type->yarn_type ?? '',
                    'balance' => $balance['balance'] ?? '',
                    'balance_amount' => $balance['balance_amount'] ?? '',
                ];
            });

        return collect($receives)->merge($receive_returns)->merge($yarn_issues)->merge($issue_returns)->merge($transfers)->groupBy('lot');
    }
}
