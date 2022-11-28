<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program;

use Illuminate\Database\Eloquent\Builder;
use PDF;
use Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnittingFloor;
use SkylarkSoft\GoRMG\Knitting\Services\ProgramDataService;
use SkylarkSoft\GoRMG\Knitting\Requests\YarnRequisitionRequest;
use SkylarkSoft\GoRMG\Knitting\Exports\YarnRequisitionListExcel;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use Throwable;
use SkylarkSoft\GoRMG\Inventory\Models\{YarnIssueDetail, YarnReceiveDetail};
use SkylarkSoft\GoRMG\Knitting\Models\{FabricSalesOrder,
    KnittingProgram,
    PlanningInfo,
    YarnAllocationDetail,
    YarnRequisition,
    YarnRequisitionDetail
};

class YarnRequisitionController extends Controller
{
    public function yarnStockSummary(Request $request): JsonResponse
    {
        $isRequisition = $request->get('isRequisition');
        $lots = array($request->get('lot'));
        if ($isRequisition) {
            $lots = YarnAllocationDetail::query()
                ->when($request->get('lot'), function ($query) use ($request) {
                    $query->where('yarn_lot', $request->get('lot'));
                })
                ->pluck('yarn_lot')->unique()->values();
        }
        $data = YarnReceiveDetail::query()
            ->with([
                'yarnReceive.factory', 'supplier', 'yarn_count', 'composition', 'type'
            ])
            ->when($request->get('factory_id'), function ($query) use ($request) {
                $query->whereHas('yarnReceive.factory', function ($q) use ($request) {
                    $q->where('factory_id', $request->get('factory_id'));
                });
            })
            ->when($request->get('supplier_id'), function ($query) use ($request) {
                $query->where('supplier_id', $request->get('supplier_id'));
            })
            ->when(!$request->get('lot') && $isRequisition, function ($query) use ($request, $lots) {
                $query->whereIn('yarn_lot', $lots);
            })
            ->when($request->get('lot') && !$isRequisition, function ($query) use ($request, $lots) {
                $query->whereIn('yarn_lot', $lots);
            })
            ->when($request->get('lot') && $isRequisition, function ($query) use ($request, $lots) {
                $query->whereIn('yarn_lot', $lots);
            })
            ->when($request->get('current_stock'), function ($query) use ($request) {
                $query->whereHas('stockSummery', function ($q) use ($request) {
                    $q->where('balance', $request->get('current_stock'));
                });
            })
            ->get()->map(function ($detail) use ($isRequisition) {
                $summary = (new YarnStockSummaryService)->summary($detail);
                $allocated_qty = YarnAllocationDetail::query()->where('yarn_lot', $detail->yarn_lot)->get()->sum('allocated_qty') ?? 0;
                $unallocated_qty = $summary->balance ?? 0 - $allocated_qty ?? 0;
                if ($isRequisition) {
                    $requisition_qty = YarnRequisitionDetail::query()->where('yarn_lot', $detail->yarn_lot)->get()->sum('requisition_qty') ?? 0;
                    $unallocated_qty = $allocated_qty - $requisition_qty;
                    $allocated_qty = $requisition_qty;
                }
                return [
                    'company' => $detail->yarnReceive->factory->factory_name ?? '',
                    'supplier' => $detail->supplier->name ?? '',
                    'supplier_id' => $detail->supplier_id,
                    'lot' => $detail->yarn_lot,
                    'yarn_count' => $detail->yarn_count->yarn_count ?? '',
                    'yarn_composition' => $detail->composition->yarn_composition ?? '',
                    'yarn_type' => $detail->type->yarn_type ?? '',
                    'yarn_brand' => $detail->yarn_brand,
                    'yarn_color' => $detail->yarn_color,
                    'current_stock' => $summary ? $summary->balance : 0,
                    'allocated_qty' => $allocated_qty,
                    'unallocated_qty' => $unallocated_qty,
                    'age_days' => $summary ? Carbon::now()->diffInDays($summary->created_at) : 0,
                    'yarn_type_id' => $detail->yarn_type_id,
                    'yarn_count_id' => $detail->yarn_count_id,
                    'yarn_composition_id' => $detail->yarn_composition_id,
                    'uom_id' => $detail->uom_id,
                    'store_id' => $detail->store_id,
                    'yarn_lot' => $detail->yarn_lot,
                ];
            });

        return response()->json($data, Response::HTTP_OK);
    }

    public function requisitionSearchFilters(): JsonResponse
    {
        $planningInfo = PlanningInfo::query()->with('knittingPrograms')->orderBy('id', 'desc')->get();
        $unique_ids = $planningInfo
            ->pluck('unique_id')
            ->unique()->values();

        $styles = $planningInfo->pluck('style_name')->unique()->values();
        $po_no = $planningInfo->pluck('po_no')->unique()->values();
        $sales_order_no = FabricSalesOrder::query()->latest()->get()->pluck('sales_order_no');
        $programNos = [];
        collect($planningInfo)->map(function ($item) use (&$programNos) {
            if ($item['knitting_program_ids']) {
                $item['knittingPrograms']->map(function ($program) use (&$programNos) {
                    $programNos[] = $program['program_no'];
                });
            }
        });

        return response()->json([
            'styles' => $styles,
            'unique_ids' => $unique_ids,
            'order_no' => $po_no,
            'sales_order_id' => $sales_order_no,
            'program_nos' => $programNos
        ]);
    }

    public function requisitionSearchData(Request $request): JsonResponse
    {
        try {
            $refNo = $request->get('ref_no');
            $buyerId = $request->get('buyer_id');
            $orderNo = $request->get('order_no');
            $programNo = $request->get('program_no');
            $uniqueId = $request->get('unique_id');
            $styleName = $request->get('style_name');
            $salesOrderId = $request->get('sales_order_id');
            $planningStatus = $request->get('planning_status');
            $bookingFactoryId = $request->get('booking_factory_id');
            $knittingFactoryId = $request->get('knitting_factory_id');
            $type = $request->input('booking_type');

            $programs = KnittingProgram::query()
                ->with(['yarnRequisition.details', 'planInfo.programmable', 'planInfo.bodyPart:id,name', 'planInfo.colorType:id,color_types'])
                ->when($knittingFactoryId, Filter::applyFilter('factory_id', $knittingFactoryId))
                ->when($planningStatus, Filter::applyFilter('status', $planningStatus))
                ->whereHas('planInfo', function ($query) use ($buyerId, $styleName, $uniqueId, $orderNo, $programNo, $type) {
                    $query->when($buyerId, Filter::applyFilter('buyer_id', $buyerId));
                    $query->when($styleName, Filter::applyFilter('style_name', $styleName));
                    $query->when($uniqueId, Filter::applyFilter('unique_id', $uniqueId));
                    $query->when($orderNo, Filter::applyFilter('po_no', $orderNo));
                    $query->when($type, Filter::applyFilter('booking_type', $type));
                })
                ->when($refNo, function (Builder $builder) use ($refNo) {
                    $builder->whereHas('yarnRequisition.details', function ($query) use ($refNo) {
                        $query->when($refNo, function ($query) use ($refNo) {
                            $yarn = YarnReceiveDetail::query()->where('product_code', $refNo)->first();
                            $query->where(YarnItemAction::itemCriteria($yarn));
                        });
                    });
                })
                ->when($salesOrderId, function ($query) use ($salesOrderId) {
                    $query->whereHas('planInfo.programmable', Filter::applyFilter('sales_order_no', $salesOrderId));
                })
                ->when($programNo, function ($query) use ($programNo) {
                    $query->whereHas('planInfo.knittingPrograms', Filter::applyFilter('program_no', $programNo));
                })
                ->latest()
                ->paginate();

            $data['total'] = $programs->total();
            $data['last_page'] = $programs->lastPage();
            $data['current_page'] = $programs->currentPage();
            $data['data'] = $programs->getCollection()->transform(function ($program) {
                $refNo = array();
                $reqInfo = collect($program->yarnRequisition)->map(function ($item) use ($program, &$refNo) {
                    $item->details->map(function ($yarn) use (&$refNo) {
                        $productCode = YarnReceiveDetail::query()->where(YarnItemAction::itemCriteria($yarn))->first()->product_code ?? '';
                        $refNo[] = $productCode;
                    });

                    return [
                        'program_id' => $program->id,
                        'requisition_no' => $item['requisition_no'],
                        'requisition_date' => $item['req_date'],
                        'req_qty' => collect($item['details'])->sum('requisition_qty'),
                    ];
                });

                $balanceQty = (double)$program->program_qty - collect($reqInfo)->sum('req_qty');
                return [
                    'program_id' => $program->id,
                    'program_no' => $program->program_no,
                    'program_date' => $program->program_date,
                    'booking_no' => $program->booking_no,
                    'booking_date' => $program->planInfo->programmable->booking_date ?? '',
                    'buyer_name' => $program->planInfo->buyer_name ?? '',
                    'style_name' => $program->planInfo->style_name ?? '',
                    'unique_id' => $program->planInfo->unique_id ?? '',
                    'order_no' => $program->planInfo->po_no ?? '',
                    'body_part' => $program->planInfo->bodyPart->name ?? '',
                    'color_type' => $program->planInfo->colorType->color_types ?? '',
                    'fabric_description' => $program->planInfo->fabric_description ?? '',
                    'fabric_gsm' => $program->planInfo->fabric_gsm ?? '',
                    'fabric_dia' => $program->planInfo->fabric_dia ?? '',
                    'dia_type' => $program->planInfo->dia_type ?? '',
                    'program_qty' => $program->program_qty ?? '',
                    'requisition_info' => $reqInfo,
                    'product_code' => collect($refNo)->implode(', '),
                    'plan_info' => $program->planInfo,
                    'sales_order_no' => $program->planInfo->programmable->sales_order_no ?? '',
                    'programmable_type' => $program->planInfo->programmable_type ?? '',
                    'balance_program_qty' => $balanceQty ? number_format($balanceQty, 4) : 0,
                ];
            });

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'msg' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(YarnRequisitionRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $requisition = YarnRequisition::query()->updateOrCreate([
                'program_id' => $request->get('program_id'),
                'requisition_no' => $request->get('requisition_no')
            ], $request->all());

            if ($request->has('id')) {
                $requisitionDetail = YarnRequisitionDetail::query()->findOrFail($request->get('id'));
                $resStatus = 200;
            } else {
                $requisitionDetail = new YarnRequisitionDetail();
                $resStatus = 201;
                $requisitionDetail->yarn_requisition_id = $requisition->id;
            }

            KnittingProgram::query()
                ->where('id', $request->get('program_id'))
                ->update(['requisition_no' => $requisition->requisition_no]);

            $requisitionDetail->fill($request->all())->save();

            DB::commit();
            return response()->json($requisition, $resStatus);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($programId): JsonResponse
    {
        try {
            $requisition = YarnRequisition::query()
                ->where('requisition_no', request()->get('req_no'))
                ->with('details.supplier', 'details.composition', 'details.yarn_count', 'details.type')
                ->first();

            $programData = (new ProgramDataService($programId))->response();

            return response()->json([
                'requisition' => $requisition,
                'programData' => $programData,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function list(Request $request)
    {
        $data = $this->getListDataFilter($request)->paginate();
        $knittingFloors = KnittingFloor::all();
        $counts = YarnCount::all();
        $yarnRequisition = YarnRequisition::query()->get()->pluck('requisition_no')->unique()->values();
        $programNo = KnittingProgram::query()->pluck('program_no')->unique()->values();

        //TODO REFACTOR;
        $dashboardOverview = [
            'Not Started' => 0,
            'In Progress' => 0,
            'On Hold' => 0,
            'Cancelled' => 0,
            'Finished' => 0
        ];

        return view('knitting::yarn-requisition.index',
            compact('data', 'knittingFloors', 'counts','dashboardOverview' ,'yarnRequisition', 'programNo'));
    }

    public function reportView($id)
    {
        $data = YarnRequisition::query()
            ->with(['details.supplier:id,name', 'details.composition', 'details.yarn_count', 'details.type', 'program.planInfo', 'knittingFloor:id,name'])
            ->findOrFail($id);
        $knittingSources = KnittingProgram::KnittingSources;
        return view('knitting::yarn-requisition.view', compact('data', 'knittingSources'));
    }

    public function reportPdf($id)
    {
        $data = YarnRequisition::query()
            ->with(['details.supplier:id,name', 'details.composition', 'details.yarn_count', 'details.type', 'program.planInfo', 'knittingFloor:id,name'])
            ->findOrFail($id);
        $knittingSources = KnittingProgram::KnittingSources;
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('knitting::yarn-requisition.pdf',
            compact('data', 'knittingSources')
        )->setPaper('a4')->setOrientation('portrait')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);
        return $pdf->stream($data->program_no . '-yarn_requisition_report.pdf');
    }

    public function delete(YarnRequisition $requisition): RedirectResponse
    {
        try {
            $issue = YarnIssueDetail::query()->where('demand_no', $requisition->requisition_no)->exists();

            if ($issue) {
                session()->flash('danger', 'Yarn issue exist for this requisition!');
            } else {
                DB::beginTransaction();
                $requisition->details()->delete();
                $requisition->delete();
                DB::commit();
                session()->flash('success', 'Deleted Successfully.');
            }
        } catch (Throwable $e) {
            session()->flash('danger', 'Something Went Wrong!');
        }
        return redirect()->back();
    }

    public function view($id): JsonResponse
    {
        try {
            $requisition = YarnRequisitionDetail::query()
                ->with('supplier')
                ->where('id', $id)
                ->firstOrFail();
            return response()->json($requisition, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkIfProgramExists($id): JsonResponse
    {
        try {
            $requisition = KnittingProgram::query()->where('id', $id)->firstOrFail();
            return response()->json($requisition, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @throws Throwable
     */
    public function storeDetails(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $requisitionData = $request->get('requisition');
            $requisition = YarnRequisition::query()->updateOrCreate([
                'program_id' => $request->get('programId'),
                'requisition_no' => $requisitionData['requisition_no']
            ], $requisitionData);

            foreach ($request->get('details') as $item) {
                $item['yarn_requisition_id'] = $requisition->id;
                $prevRequisition = YarnRequisitionDetail::query()
                    ->where('knitting_program_color_id', $item['knitting_program_color_id'])
                    ->where('yarn_requisition_id', $requisition->id)
                    ->where(YarnItemAction::itemCriteria($item))
                    ->first();

                if ($prevRequisition) {
                    $prevRequisition->update([
                        'requisition_qty' => (double)$prevRequisition->requisition_qty + ($item['requisition_qty'] ?? 0),
                        'requisition_date' => $item['requisition_date'] ?? '',
                        'remarks' => $item['remarks'] ?? '',
                    ]);
                } else {
                    YarnRequisitionDetail::query()->create($item);
                }
            }

            DB::commit();
            return response()->json($requisition, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function listExcelAll(Request $request)
    {
        try {
            $data = $this->getListDataFilter($request)->get();
            return Excel::download(new YarnRequisitionListExcel($data), 'yarn-requisition-list-all.xlsx');
        } catch (Exception|\PhpOffice\PhpSpreadsheet\Exception $e) {
            return back()->with(['alert-danger', 'Something went wrong!']);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function listExcelByPage(Request $request)
    {
        try {
            $page = (int)$request->get('page');
            $data = $this->getListDataFilter($request)->paginate(15, ['*'], 'page', $page);;
            return Excel::download(new YarnRequisitionListExcel($data), "yarn-requisition-list-of-page-no-" . $page . ".xlsx");
        } catch (Exception|\PhpOffice\PhpSpreadsheet\Exception $e) {
            return back()->with(['alert-danger', 'Something went wrong!']);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListDataFilter($request)
    {
        $buyer = $request->get('buyer');
        $style = $request->get('style');
        $reqDate = $request->get('req_date');
        $yarn_lot = $request->get('yarn_lot');
        $programNo = $request->get('program_no');
        $yarn_brand = $request->get('yarn_brand');
        $type = $request->get('type');
        $booking_no = $request->get('booking_no');
        $yarn_count = $request->get('yarn_count');
        $within_group = $request->get('within_group');
        $requisitionNo = $request->get('requisition_no');
        $sales_order_no = $request->get('sales_order_no');
        $knitting_source = $request->get('knitting_source');
        $knittingFloorId = $request->get('knitting_floor_id');

        return YarnRequisition::query()
            ->with(['program.planInfo.programmable', 'details', 'details.composition', 'details.yarn_count', 'details.type', 'knittingFloor'])
            ->withSum('yarnIssue', 'issue_qty')
            ->when($requisitionNo, Filter::applyFilter('requisition_no', $requisitionNo))
            ->when($programNo, Filter::applyFilter('program_id', (int)$programNo))
            ->when($reqDate, Filter::applyFilter('req_date', $reqDate))
            ->when($knittingFloorId, Filter::applyFilter('knitting_floor_id', $knittingFloorId))
            ->whereHas('program', function ($query) use ($knitting_source) {
                $query->when($knitting_source, Filter::applyFilter('knitting_source_id', $knitting_source));
            })
            ->whereHas('program.planInfo', function ($query) use ($buyer) {
                $query->when($buyer, Filter::applyFilter('buyer_name', $buyer));
            })
            ->whereHas('program.planInfo', function ($query) use ($style) {
                $query->when($style, Filter::applyFilter('style_name', $style));
            })
            ->whereHas('program.planInfo', function ($query) use ($booking_no) {
                $query->when($booking_no, Filter::applyFilter('booking_no', $booking_no));
            })
            ->whereHas('program.planInfo', function ($query) use ($type) {
                $query->when($type, Filter::applyFilter('booking_type', $type));
            })
            ->whereHas('program.planInfo.programmable', function ($query) use ($sales_order_no, $within_group) {
                $query->when($within_group, Filter::applyFilter('within_group', $within_group));
                $query->when($sales_order_no, Filter::applyFilter('sales_order_no', $sales_order_no));
            })
            ->whereHas('details', function ($query) use ($yarn_count, $yarn_brand, $yarn_lot) {
                $query->when($yarn_count, Filter::applyFilter('yarn_count_id', $yarn_count));
                $query->when($yarn_brand, Filter::applyFilter('yarn_brand', $yarn_brand));
                $query->when($yarn_lot, Filter::applyFilter('yarn_lot', $yarn_lot));
            })
            ->latest();
    }
}
