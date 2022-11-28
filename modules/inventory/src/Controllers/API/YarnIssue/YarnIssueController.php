<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssue;

use App\Exceptions\DivisionByZeroException;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueStockService;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisition;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisitionDetail;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssue;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\Inventory\Requests\YarnIssueFormRequest;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Exceptions\DateNotAvailableException;

class YarnIssueController extends Controller
{
    public function index(Request $request)
    {
        $view['supplier'] = Supplier::query()->get();
        $view['issueBasis'] = YarnIssue::ISSUE_BASIS;
        $view['issuePurposes'] = YarnIssue::ISSUE_PURPOSE;
        $view['requisitionNos'] = YarnRequisition::query()->latest()->pluck('requisition_no');

        $ref_no = $request->input('ref_no');
        $lot = $request->input('lot');
        $yarn_count = $request->input('yarn_count');
        $issue_no = $request->input('issue_no');
        $challan_no = $request->input('challan_no');
        $issue_date = $request->input('issue_date');
        $issue_basis = $request->input('issue_basis');
        $supplier_id = $request->input('supplier_id');
        $issue_purpose = $request->input('issue_purpose');
        $gate_pass_no = $request->input('gate_pass_no');
        $requisitionNo = $request->input('requisition_no');
        $type = $request->input('type');

        $view['data'] = YarnIssue::query()->with(['details.yarn_count', 'details.requisition:id,requisition_no', 'issueReturn.details'])
            ->when($issue_no, function (Builder $builder) use ($issue_no) {
                $builder->where('issue_no', $issue_no);
            })
            ->when($issue_basis, function (Builder $builder) use ($issue_basis) {
                $builder->where('issue_basis', $issue_basis);
            })
            ->when($challan_no, function (Builder $builder) use ($challan_no) {
                $builder->where('challan_no', $challan_no);
            })
            ->when($supplier_id, function (Builder $builder) use ($supplier_id) {
                $builder->where('supplier_id', $supplier_id);
            })
            ->when($issue_purpose, function (Builder $builder) use ($issue_purpose) {
                $builder->where('issue_purpose', $issue_purpose);
            })
            ->when($issue_date, function (Builder $builder) use ($issue_date) {
                $builder->where('issue_date', $issue_date);
            })
            ->when($gate_pass_no, function (Builder $builder) use ($gate_pass_no) {
                $builder->where('gate_pass_no', $gate_pass_no);
            })
            ->when($lot || $ref_no || $yarn_count || $type, function (Builder $builder) use ($lot, $ref_no, $yarn_count, $type) {
                $builder->whereHas('details', function ($query) use ($lot, $ref_no, $yarn_count, $type) {
                    $query->when($type, function ($q) use ($type) {
                        $q->where('booking_type', $type);
                    });
                    $query->when($lot, function ($q) use ($lot) {
                        $q->where('yarn_lot', $lot);
                    });
                    $query->when($yarn_count, function ($q) use ($yarn_count) {
                        $q->whereHas('yarn_count', function ($q) use ($yarn_count) {
                            $q->where('yarn_count', $yarn_count);
                        });
                    });
                    $query->when($lot && $yarn_count, function ($q) use ($lot, $yarn_count) {
                        $q->where('yarn_lot', $lot)->whereHas('yarn_count', function ($q) use ($yarn_count) {
                            $q->where('yarn_count', $yarn_count);
                        });
                    });
                    $query->when($ref_no, function ($query) use ($ref_no) {
                        $yarn = YarnReceiveDetail::query()->where('product_code', $ref_no)->first();
                        $query->where(YarnItemAction::itemCriteria($yarn));
                    });
                });
            })
            ->when($requisitionNo, function (Builder $builder) use ($requisitionNo) {
                $builder->whereHas('details.requisition', Filter::applyFilter('requisition_no', $requisitionNo));
            })
            ->orderByDesc('id')
            ->paginate();

        $view['data']->map(function ($yarn) {
            $yarn->details->map(function ($details) {
                $productCode = YarnReceiveDetail::query()->where(YarnItemAction::itemCriteria($details))->first()->product_code ?? '';
                $details['product_code'] = $productCode;
                return $details;
            });
        });

        return view('inventory::yarns.yarn-issue.index', $view);
    }

    public function create($id = null)
    {
        return view('inventory::yarns.yarn-issue.create');
    }

    public function getYarnReceive(Request $request): JsonResponse
    {
        $value = $request->get('value');
        $storeId = $request->get('store_id');
        $searchBy = $request->get('search_by');
        $supplierId = $request->get('supplier_id');

        $yarnReceive = YarnReceive::query()
            ->with('details')
            ->whereHas('details', function ($query) use ($value, $searchBy, $storeId, $supplierId) {
                return $query->when($storeId, Filter::applyFilter('store_id', $storeId));
            })
            ->whereHas('details', function ($query) use ($value, $searchBy, $storeId, $supplierId) {
                return $query->when($supplierId, Filter::applyFilter('supplier_id', $supplierId));
            })
            ->whereHas('details', function ($query) use ($value, $searchBy, $storeId, $supplierId) {
                return $query->when($searchBy == 'lot-no', Filter::applyFilter('yarn_lot', $value));
            })
            ->whereHas('details', function ($query) use ($value, $searchBy, $storeId, $supplierId) {
                return $query->when($searchBy == 'yarn-count', Filter::applyFilter('yarn_count_id', $value));
            })
            ->orderByDesc('id')
            ->get()
            ->map(function ($receive) use ($supplierId, $storeId, $searchBy, $value) {
                $receiveDetails = YarnReceiveDetail::query()->where('yarn_receive_id', $receive->id)
                    ->when($storeId, Filter::applyFilter('store_id', $storeId))
                    ->when($supplierId, Filter::applyFilter('supplier_id', $supplierId))
                    ->when($searchBy == 'lot-no', Filter::applyFilter('yarn_lot', $value))
                    ->when($searchBy == 'yarn-count', Filter::applyFilter('yarn_count_id', $value))
                    ->get()
                    ->map(function ($detail) {
                        $summary = (new YarnStockSummaryService())->summary($detail);
                        return [
                            'supplier_id' => $detail->supplier_id,
                            'supplier' => $detail->supplier->name,
                            'yarn_brand' => $detail->yarn_brand,
                            'yarn_count' => $detail->yarn_count->yarn_count,
                            'yarn_count_id' => $detail->yarn_count_id,
                            'yarn_type' => optional($detail->type)->name,
                            'yarn_type_id' => $detail->yarn_type_id,
                            'yarn_composition_id' => $detail->yarn_composition_id,
                            'yarn_composition' => $detail->composition->yarn_composition,
                            'yarn_color' => $detail->yarn_color,
                            'yarn_lot' => $detail->yarn_lot,
                            'uom_id' => $detail->uom_id,
                            'uom' => $detail->uom->unit_of_measurement,
                            'weight_per_bag' => $detail->weight_per_bag,
                            'weight_per_cone' => $detail->weight_per_cone,
                            'floor' => $detail->floor->name,
                            'floor_id' => $detail->floor_id,
                            'room' => $detail->room->name,
                            'room_id' => $detail->room_id,
                            'rack' => $detail->rack->name,
                            'rack_id' => $detail->rack_id,
                            'shelf' => $detail->shelf->name,
                            'shelf_id' => $detail->shelf_id,
                            'bin' => $detail->bin->name,
                            'bin_id' => $detail->bin_id,
                            'rate' => $detail->rate,
                            'current_stock' => $summary->balance,
                            'receive_qty' => $detail->receive_qty,
                        ];
                    });
                return [
                    'lc_no' => $receive->lc_no,
                    'receive_no' => $receive->receive_no,
                    'challan_no' => $receive->challan_no,
                    'receive_date' => $receive->receive_date,
                    'total_qty' => $receive->details->sum('receive_qty'),
                    'yarn_counts' => collect($receiveDetails)->pluck('yarn_count')->unique()->join(', '),
                    'lots' => collect($receiveDetails)->pluck('yarn_lot')->unique()->join(', '),
                    'details' => $receiveDetails,
                ];
            });
        return response()->json($yarnReceive, Response::HTTP_OK);
    }

    public function getGarmentType($id): JsonResponse
    {
        $garmentsSample = GarmentsSample::query()
            ->where('factory_id', $id)
            ->get(['id', 'name as text']);
        return response()->json($garmentsSample);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'factory_id' => 'required',
            'issue_date' => 'required',
            'issue_purpose' => 'required',
            'supplier_id' => 'required',
            'issue_to' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $yarnIssue = YarnIssue::query()->updateOrCreate(['id' => $request->get('id')]);
            $yarnIssue->fill($request->all())->save();
            DB::commit();
            return response()->json($yarnIssue, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function storeDetails(YarnIssueFormRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $yarnIssueDetail = YarnIssueDetail::query()->firstOrNew(['id' => $request->get('id')]);

            if ($request->get('id')) {
                (new YarnIssueStockService())->updated($yarnIssueDetail);
                $updateData = $request->except(['uom_id', 'yarn_lot', 'store_id', 'yarn_color', 'yarn_brand', 'yarn_type_id', 'yarn_count_id', 'yarn_composition_id']);
                $yarnIssueDetail->fill($updateData)->save();
            } else {
                $yarnIssueDetail->fill($request->all())->save();
                (new YarnIssueStockService())->created($yarnIssueDetail);
            }

            if ($request->get('challan_no')) {
                YarnIssue::query()->where('id', $yarnIssueDetail->yarn_issue_id)
                    ->update(['challan_no' => $request->get('challan_no')]);
            }

            DB::commit();
            return response()->json($yarnIssueDetail, Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): JsonResponse
    {
        $data['issue'] = YarnIssue::query()->findOrFail($id);
        $data['details'] = YarnIssueDetail::query()->where('yarn_issue_id', $id)
            ->with([
                'composition', 'yarn_count', 'type', 'floor', 'room', 'rack', 'shelf', 'bin', 'store', 'issue.supplier', 'uom'
            ])
            ->orderByDesc('id')
            ->get()->map(function ($yarn) {
                $summary = (new YarnStockSummaryService())->summary($yarn);
                $rate = 0;
                if ($summary->receive_amount != 0 && $summary->receive_qty != 0) {
                    $rate = $summary->receive_amount / $summary->receive_qty;
                }
                return array_merge(
                    $yarn->toArray(), [
                        'current_stock' => $summary->balance + $yarn->issue_qty,
                        'rate' => number_format($rate, 2, '.', ''),
                        'receive_qty' => $summary->receive_qty,
                    ]
                );
            });

        return response()->json($data, Response::HTTP_OK);
    }


    /**
     * @throws Throwable
     */
    public function delete($id): RedirectResponse
    {
        try {
            $yarnIssue = YarnIssue::query()->find($id);
            if ($yarnIssue->details->count() || $yarnIssue->issueReturn->count() || $yarnIssue->is_approved) {
                return redirect()->back()->with('danger', 'Delete Failed!');
            }
            $yarnIssue->delete();
            return redirect()->back()->with('success', 'Delete Success');
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', 'Delete Failed!');
        }

    }

    /**
     * @throws Throwable
     */
    public function deleteDetails($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $yarn = YarnIssueDetail::query()->findOrFail($id);
            (new YarnIssueStockService())->deleted($yarn);

            $yarn->delete();
            DB::commit();
            return response()->json('Data Deleted!', Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_OK);
        }
    }

    public function view($id)
    {
        $data = YarnIssue::with([
            'details.composition', 'details.yarn_count', 'details.type', 'details.floor', 'details.room', 'details.rack', 'details.shelf', 'details.bin', 'details.store', 'supplier', 'details.uom'
        ])->findOrFail($id);

        return view('inventory::yarns.yarn-issue.view', compact('data'));
    }

    public function print($id)
    {
        $data = YarnIssue::with([
            'details.composition',
            'details.yarn_count',
            'details.floor',
            'details.type',
            'details.bin',
            'details.room',
            'details.rack',
            'details.shelf',
            'details.store',
            'details.uom',
            'supplier',
        ])->findOrFail($id);

        return view('inventory::yarns.yarn-issue.print', compact('data'));
    }

    public function getYarnLot($supplierId): JsonResponse
    {
        $lot = YarnReceiveDetail::query()->where('supplier_id', $supplierId)
            ->get()->pluck('yarn_lot')
            ->unique()->values()->map(function ($lot) {
                return [
                    'id' => $lot,
                    'text' => $lot
                ];
            });

        return response()->json($lot);
    }

    public function approval($id): RedirectResponse
    {
        $issue = YarnIssue::query()->findOrFail($id);

        if ($issue->is_approved) {
            $issue->update(['is_approved' => false]);
        } else {
            $issue->update(['is_approved' => true]);
        }

        $status = $issue->is_approved ? 'Approved' : 'Unapproved';
        return redirect()->back()->with('success', "$status Successful");
    }

    public function requisitionValidationData($issueDetailsId)
    {
        $issueDetails = YarnIssueDetail::query()->find($issueDetailsId);
        $reqId = YarnRequisition::query()->where('requisition_no', $issueDetails->demand_no)->first()->id ?? '';
        $reqQty = YarnRequisitionDetail::query()->where('yarn_requisition_id', $reqId)
                ->where(YarnItemAction::itemCriteria($issueDetails))
                ->sum('requisition_qty') ?? 0;
        $existingIssuedRequisition = YarnIssueDetail::query()->where('demand_no', $issueDetails->demand_no)
            ->where(YarnItemAction::itemCriteria($issueDetails))
            ->sum('issue_qty');

        $response = [
            'requisition_id' => $reqId,
            'issue_qty' => $issueDetails->issue_qty,
            'total_requisition_qty' => $reqQty,
            'total_issued_requisition' => $existingIssuedRequisition,
            'total_issued_requisition_except_editable_qty' => $existingIssuedRequisition - $issueDetails->issue_qty
        ];

        return response()->json($response);
    }

    public function requisitionSearchFilterData(): JsonResponse
    {
        try {
            $requisitionNos = YarnRequisition::query()->latest()->pluck('requisition_no');
            $lots = YarnRequisitionDetail::query()->latest()->pluck('yarn_lot')->unique()->values();
            return response()->json([
                'requisition_nos' => $requisitionNos,
                'lots' => $lots
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
