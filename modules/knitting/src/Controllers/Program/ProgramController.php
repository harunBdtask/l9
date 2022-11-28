<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program;

use App\Exceptions\DeleteNotPossibleException;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\Knitting\Actions\KnittingProgram\CollarCuffDetailsAction;
use SkylarkSoft\GoRMG\Knitting\Actions\KnittingProgram\KnittingProgramInPlanInfoAction;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;
use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocationDetail;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisition;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisitionDetail;
use SkylarkSoft\GoRMG\Knitting\Requests\ProgramRequest;
use SkylarkSoft\GoRMG\Knitting\Services\ProgramDataService;
use SkylarkSoft\GoRMG\Merchandising\Directives\BladeDirectiveCriteria;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $buyerId = $request->input('buyer_id');
        $styleName = $request->input('style_name');
        $bookingNo = $request->input('booking_no');
        $uniqueId = $request->input('unique_id');
        $withinGroup = $request->input('within_group');
        $type = $request->input('type');

        $companies = Factory::all();
        $planningInfo = PlanningInfo::query()->get();
        $booking_no = $planningInfo->pluck('booking_no')->unique();

        $data = PlanningInfo::query()
            ->with(['bodyPart', 'colorType', 'programmable', 'knittingPrograms', 'order'])
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($bookingNo, Filter::applyFilter('booking_no', $bookingNo))
            ->when($uniqueId, Filter::applyFilter('unique_id', $uniqueId))
            ->when($styleName, function ($builder) use ($styleName) {
                $builder->where('style_name', 'like', '%' . $styleName . '%');
            })
            ->when($type, function ($query) use ($type) {
                $query->where('booking_type', $type);
            })
            ->when($withinGroup, function ($query) use ($withinGroup) {
                $query->whereHas('programmable', function ($query) use ($withinGroup) {
                    $query->where('within_group', $withinGroup);
                });
            })
            ->orderByDesc('id')
            ->paginate();


        return view('knitting::program.index', compact('companies', 'booking_no', 'data'));
    }

    public function list()
    {
        $programNo = request('program_no');
        $programDate = request('program_date');
        $programQty = request('program_qty');
        $knittingSourceId = request('knitting_source_id');
        $knittingPartyId = request('knitting_party_id');
        $finishFabricDia = request('finish_fabric_dia');
        $machineDia = request('machine_dia');
        $machineGg = request('machine_gg');
        $withinGroup = request('within_group');
        $buyerId = request('buyer_id');
        $styleName = request('style_name');
        $salesOrderNo = request('sales_order_no');
        $bookingNo = request('booking_no');
        $type = request('type');
        $bodyPart = request('body_part_id');
        $status = request('status');
        $partyType = $knittingSourceId == 1 ? 'Factory' : 'Supplier';

        $data['search_programs'] = KnittingProgram::query()
            ->get(['program_no']);

        $data['programs'] = KnittingProgram::query()
            ->when($programNo, Filter::applyFilter('program_no', $programNo))
            ->when($programDate, Filter::applyFilter('program_date', $programDate))
            ->when($programQty, Filter::applyFilter('program_qty', $programQty))
            ->when($programQty, Filter::applyFilter('program_qty', $programQty))
            ->when($knittingSourceId, Filter::applyFilter('knitting_source_id', $knittingSourceId))
            ->when($knittingPartyId, Filter::applyFilter('knitting_party_id', $knittingPartyId))
            ->when($knittingPartyId, Filter::applyFilter('knitting_party_type', $partyType))
            ->when($finishFabricDia, Filter::applyFilter('finish_fabric_dia', $finishFabricDia))
            ->when($machineDia, Filter::applyFilter('machine_dia', $machineDia))
            ->when($machineGg, Filter::applyFilter('machine_gg', $machineGg))
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($status, Filter::applyFilter('status', $status))
            ->when($withinGroup, function ($q) use ($withinGroup) {
                $q->whereHas('planInfo.programmable', function ($query) use ($withinGroup) {
                    return $query->where('within_group', $withinGroup);
                });
            })
            ->when($styleName, function ($q) use ($styleName) {
                $q->whereHas('planInfo.programmable', function ($query) use ($styleName) {
                    return $query->where('style_name', 'like', '%' . $styleName . '%');
                });
            })
            ->when($salesOrderNo, function ($q) use ($salesOrderNo) {
                $q->whereHas('planInfo.programmable', function ($query) use ($salesOrderNo) {
                    return $query->where('sales_order_no', $salesOrderNo);
                });
            })
            ->when($bookingNo, function ($q) use ($bookingNo) {
                $q->whereHas('planInfo', function ($query) use ($bookingNo) {
                    return $query->where('booking_no', $bookingNo);
                });
            })
            ->when($type, function ($q) use ($type) {
                $q->whereHas('planInfo', function ($query) use ($type) {
                    return $query->where('booking_type', $type);
                });
            })
            ->when($bodyPart, function ($q) use ($bodyPart) {
                $q->whereHas('planInfo', function ($query) use ($bodyPart) {
                    return $query->where('body_part', $bodyPart);
                });
            })
            ->with(['knittingParty', 'planInfo.programmable', 'planInfo.bodyPart', 'buyer'])
            ->withSum('knitCard', 'assign_qty')
            ->orderByDesc('created_at')
            ->paginate();

        $data['knittingParties'] = Factory::query()->get(['id', 'factory_name']);
        $data['knittingSources'] = KnittingProgram::KnittingSources;
        $data['withinGroups'] = FabricSalesOrder::WITHIN_GROUP;
        $data['buyers'] = Buyer::query()->where('factory_id', factoryId())->get(['id', 'name']);
        $planningInfo = PlanningInfo::query();
        $data['salesOrderNos'] = FabricSalesOrder::query()->pluck('sales_order_no')->unique()->values();
        $data['styles'] = $planningInfo->pluck('style_name')->unique();
        $data['bookingNos'] = $planningInfo->pluck('booking_no')->unique();
        $data['bodyParts'] = BodyPart::query()->get(['id', 'name']);
        //TODO REFACTOR;


        $data['dashboardOverview'] = [
            'Not Started' => KnittingProgram::query()->where('status', 'waiting')->count(),
            'In Progress' => KnittingProgram::query()->where('status', 'running')->count(),
            'On Hold' => 0,
            'Cancelled' => 0,
            'Finished' => KnittingProgram::query()->where('status', 'stop')->count()
        ];


        return view('knitting::program.list', compact('data'));
    }

    public function view($id)
    {
        $data = (new ProgramDataService($id))->response();
        $knittingSources = KnittingProgram::KnittingSources;
        return view('knitting::program.view', compact('data', 'knittingSources'));
    }

    public function pdf($id)
    {
        $data = (new ProgramDataService($id))->response();
        $knittingSources = KnittingProgram::KnittingSources;
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('knitting::program.pdf',
            compact('data', 'knittingSources')
        )->setPaper('a4')->setOrientation('portrait')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);
        return $pdf->stream($data->program_no . '-knitting_program_report.pdf');
    }

    public function create(Request $request)
    {
        return view('knitting::program.create');
    }

    /**
     * @param ProgramRequest $request
     * @param CollarCuffDetailsAction $collarCuffDetailsAction
     * @return JsonResponse
     * @throws Throwable
     */

    public function store(ProgramRequest $request, CollarCuffDetailsAction $collarCuffDetailsAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $knittingProgram = KnittingProgram::query()->firstOrNew(['id' => $request->input('id')]);
            $knittingProgram->fill($request->all())->save();

            $knittingProgram->update([
                'booking_no' => $knittingProgram->planInfo->booking_no,
                'fabric_description' => $knittingProgram->planInfo->fabric_description
            ]);

            KnittingProgramInPlanInfoAction::handle($knittingProgram->plan_info_id);
            $collarCuffDetailsAction->handle($knittingProgram);
            DB::commit();
            return response()->json($knittingProgram, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(KnittingProgram $knittingProgram): JsonResponse
    {
        try {
            $knittingProgram->load('machines.machine');
            return response()->json($knittingProgram, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @throws Throwable
     */
    public function delete(KnittingProgram $knittingProgram): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            throw_if($knittingProgram->yarnAllocation()->exists(), new DeleteNotPossibleException('Yarn Allocated for this program!'));
            throw_if($knittingProgram->knitCard()->exists(), new DeleteNotPossibleException('Knit Card Exists for this program!'));

            $planInfoId = $knittingProgram->plan_info_id;
            $knittingProgram->knitting_program_colors_qtys()->delete();
            $knittingProgram->delete();

            KnittingProgramInPlanInfoAction::handle($planInfoId);

            DB::commit();
            session()->flash('success', 'Successfully Deleted');
        } catch (Exception $exception) {
            session()->flash('danger', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function updateFleece(Request $request, KnittingProgram $knittingProgram): JsonResponse
    {
        try {
            $knittingProgram->update([
                'fleece_info' => $request->input('fleece_info')
            ]);
            return response()->json($knittingProgram, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function allocateStore(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            if ($this->isRequisitionExists($request->all())) {
                return response()->json('Requisition exist for this allocation!', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $yarnAllocationDetail = YarnAllocationDetail::query()->updateOrCreate([
                'yarn_count_id' => $request->get('yarn_count_id'),
                'yarn_composition_id' => $request->get('yarn_composition_id'),
                'yarn_type_id' => $request->get('yarn_type_id'),
                'yarn_lot' => $request->get('yarn_lot'),
                'uom_id' => $request->get('uom_id'),
                'yarn_color' => $request->get('yarn_color'),
                'yarn_brand' => $request->get('yarn_brand'),
                'store_id' => $request->get('store_id'),
                'knitting_program_id' => $request->get('knitting_program_id'),
                'knitting_program_color_id' => $request->get('knitting_program_color_id'),
            ], $request->all());

            DB::commit();
            return response()->json($yarnAllocationDetail, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Something Went Wrong!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * @throws Throwable
     */
    public function yarnAllocationDelete($allocationId): JsonResponse
    {
        try {
            DB::beginTransaction();
            $yarnAllocationDetail = YarnAllocationDetail::query()->find($allocationId);

            if ($this->isRequisitionExists($yarnAllocationDetail)) {
                return response()->json('Requisition exist for this allocation!', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $yarnAllocationDetail->delete();
            DB::commit();
            return response()->json('Deleted Successfully', Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function isRequisitionExists($detail): bool
    {
        return YarnRequisition::query()->with('details')
            ->where('program_id', $detail['knitting_program_id'])
            ->whereHas('details', function ($query) use ($detail) {
                $query->where('knitting_program_color_id', $detail['knitting_program_color_id'])
                    ->where(YarnItemAction::itemCriteria($detail));
            })->exists();
    }

    public function yarnStockSummaryForAllocation(Request $request): JsonResponse
    {
        $factoryId = $request->get('factory_id');
        $storeId = $request->get('store_id');
        $colorId = $request->get('color_id');
        $programId = $request->get('program_id');
        $yarnLot = $request->get('yarn_lot');
        $yarnBrand = $request->get('yarn_brand');
        $refNo = $request->get('ref_no');
        $yarnColor = $request->get('yarn_color');
        $yarnCountId = $request->get('yarn_count_id');

        $yarnStockSummary = YarnStockSummary::query()->with(['company:id,factory_name'])
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->when($storeId, Filter::applyFilter('store_id', $storeId))
            ->when($yarnLot, Filter::applyFilter('yarn_lot', $yarnLot))
            ->when($yarnBrand, Filter::applyFilter('yarn_brand', $yarnBrand))
            ->when($yarnColor, Filter::applyFilter('yarn_color', $yarnColor))
            ->when($yarnCountId, Filter::applyFilter('yarn_count_id', $yarnCountId))
            ->when($refNo, function ($query) use ($refNo) {
                $yarn = YarnReceiveDetail::query()->where('product_code', $refNo)->first();
                $query->where(YarnItemAction::itemCriteria($yarn));
            })
            ->orderByDesc('created_at')
            ->paginate();

        $data['total'] = $yarnStockSummary->total();
        $data['last_page'] = $yarnStockSummary->lastPage();
        $data['current_page'] = $yarnStockSummary->currentPage();
        $data['data'] = $yarnStockSummary->getCollection()->transform(function ($yarnStockSummary) use ($programId, $colorId) {
            $allocation = $this->getYarnAllocation($yarnStockSummary);
            $colorWiseAllocation = clone $allocation;
            $itemTotalAllocationQty = $allocation->sum('allocated_qty');
            $colorTotalAllocationQty = $colorWiseAllocation->where('knitting_program_id', $programId)
                ->where('knitting_program_color_id', $colorId)
                ->sum('allocated_qty');
            $dateWiseYarnReceive = $this->getDateWiseYarnStock($yarnStockSummary);
            $yarnReceiveDetail = YarnReceiveDetail::query()->where(YarnItemAction::itemCriteria($yarnStockSummary))->get();
            $totalUnallocatedQty = $yarnReceiveDetail->sum('receive_qty') - $itemTotalAllocationQty;

            $totalIssueQty = YarnIssueDetail::query()
                ->whereHas('issue', function ($q) {
                    $q->where('issue_basis', 2);
                })
                ->where(YarnItemAction::itemCriteria($yarnStockSummary))
                ->sum('issue_qty');

            return [
                'company' => $yarnStockSummary->company->factory_name,
                'lot' => $yarnStockSummary->yarn_lot,
                'yarn_count' => $yarnStockSummary->meta['yarn_count'] ?? '',
                'yarn_composition' => $yarnStockSummary->meta['yarn_composition'] ?? '',
                'yarn_type' => $yarnStockSummary->meta['yarn_type'] ?? '',
                'yarn_brand' => $yarnStockSummary->yarn_brand,
                'yarn_color' => $yarnStockSummary->yarn_color,

                'current_stock' => $yarnStockSummary->balance,
                'total_allocated_qty' => $itemTotalAllocationQty,
                'allocated_qty' => $colorTotalAllocationQty,
                'unallocated_qty' => $totalUnallocatedQty, //$yarnStockSummary->balance - $itemTotalUnissuedQty,

                'age_days' => $dateWiseYarnReceive ? Carbon::now()->diffInDays($dateWiseYarnReceive->date) : 0,
                'yarn_type_id' => $yarnStockSummary->yarn_type_id,
                'yarn_count_id' => $yarnStockSummary->yarn_count_id,
                'yarn_composition_id' => $yarnStockSummary->yarn_composition_id,
                'uom_id' => $yarnStockSummary->uom_id,
                'store_id' => $yarnStockSummary->store_id,
                'yarn_lot' => $yarnStockSummary->yarn_lot,
                'rem_issue_qty' => $itemTotalAllocationQty > 0 ? (($itemTotalAllocationQty - $totalIssueQty) ?? 0) : $totalIssueQty,
                'supplier_id' => $this->getYarnReceive($yarnStockSummary)->supplier_id ?? null,
                'no_of_bag' => $yarnReceiveDetail->first()->no_of_bag ?? '',
                'product_code' => $yarnReceiveDetail->first()->product_code ?? ''
            ];
        });

        return response()->json($data, Response::HTTP_OK);
    }

    public function getYarnAllocation($yarn)
    {
        return YarnAllocationDetail::query()
            ->where(YarnItemAction::itemCriteria($yarn))
            ->get()
            ->map(function ($item) use ($yarn) {
                return [
                    'knitting_program_id' => $item->knitting_program_id,
                    'knitting_program_color_id' => $item->knitting_program_color_id,
                    'allocated_qty' => $item->allocated_qty,
                    'issue_status' => $this->checkIfIssueExists($item, $yarn)
                ];
            });
    }

    private function checkIfIssueExists($allocation, $yarn): bool
    {
        $requisitionDetails = YarnRequisitionDetail::query()
            ->whereHas('yarnRequisition', Filter::applyFilter('program_id', $allocation->knitting_program_id))
            ->where('knitting_program_color_id', $allocation->knitting_program_color_id)
            ->where(YarnItemAction::itemCriteria($yarn))
            ->with('yarnRequisition')
            ->get();

        $status = false;
        foreach ($requisitionDetails as $detail) {
            if (count($detail->yarnRequisition->yarnIssue) > 0) {
                $status = true;
                break;
            }
        }
        return $status;
    }

    public function getDateWiseYarnStock($yarnStockSummary)
    {
        return YarnDateWiseStockSummary::query()
            ->where(YarnItemAction::itemCriteria($yarnStockSummary))
            ->orderByDesc('date')
            ->first();
    }

    public function getYarnReceive($yarn)
    {
        return YarnReceiveDetail::query()
            ->where(YarnItemAction::itemCriteria($yarn))
            ->first();
    }

    public function programView(Request $request, $id)
    {
        $program = KnittingProgram::query()
            ->with([
                'buyer',
                'planInfo.order',
                'knittingProgramStripeDetails'
            ])
            ->find($id);
        $knittingSources = KnittingProgram::KnittingSources;
        $data = (new ProgramDataService($id))->response();
        //dd($program);
        return view('knitting::program.program-view', [
            'program' => $program,
            'knittingSources' => $knittingSources,
            'data' => $data
        ]);
    }

    public function programViewPdf(Request $request, $id)
    {
        # code...
        $data = (new ProgramDataService($id))->response();
        $program = KnittingProgram::query()
            ->with([
                'buyer',
                'planInfo.order',
                'knittingProgramStripeDetails'
            ])
            ->find($id);
        $knittingSources = KnittingProgram::KnittingSources;
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView('knitting::program.program-view-pdf',
            compact('data', 'knittingSources', 'program')
        )->setPaper('a4')->setOrientation('portrait')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);
        return $pdf->stream($data->program_no . '-knitting_program_report.pdf');
    }

    public function deletePermission(): JsonResponse
    {
        $response = BladeDirectiveCriteria::permission('permission_of_program_list_view');
        return response()->json($response);
    }

    public function getExistingAllocation(Request $request): JsonResponse
    {
        try {
            $data = YarnAllocationDetail::query()
                ->with(['program', 'programColor:id,knitting_program_id,item_color'])
                ->where(YarnItemAction::itemCriteria($request->get('data')))
                ->where('knitting_program_id', '!=', (int)$request->get('program'))
                ->get()
                ->map(function ($item) {
                    return [
                        'program_no' => $item->program->program_no ?? '',
                        'program_color' => $item->programColor->item_color ?? '',
                        'allocated_qty' => $item->allocated_qty,
                        'requisition_status' => $item->previous_total_yarn_requisition_qty > 0,
                    ];
                });
            return response()->json(['data' => $data, 'total_allocated_qty' => collect($data)->sum('allocated_qty')], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProgramColorPreview(KnittingProgram $knittingProgram): JsonResponse
    {
        try {
            $views = array();
            $knittingProgram->load('knitting_program_colors_qtys');
            $views = view('knitting::program.program_color_preview', [
                'program' => $knittingProgram
            ])->render();

            return response()->json($views, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}



