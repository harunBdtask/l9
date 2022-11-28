<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;
use SkylarkSoft\GoRMG\Knitting\Actions\KnitCard\KnitCardYarnDetailsStore;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\{FabricSalesOrder,
    KnitCard,
    KnitCardYarnDetail,
    KnittingProgram,
    KnittingProgramColorsQty};
use SkylarkSoft\GoRMG\Knitting\Services\ProgramDataService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class KnitCardController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $q = $request->all()??null;
        $search = null;
        $type = null;
        if (!empty($q)) {
            $search = $q['search'];
            $type = $q['type'];
        }
        $data = KnitCard::query()
            ->with(['planInfo', 'factory', 'buyer', 'user', 'program'])
            ->when($type, function ($query) use ($type) {
                $query->whereHas('planInfo', function ($query) use ($type) {
                    $query->where('booking_type', $type);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where('knit_card_no', "LIKE", "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate();
        $dashboardOverview = [];
        return view('knitting::knit-card.index', compact('data', 'dashboardOverview'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('knitting::knit-card.create');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getProgramNo(Request $request): JsonResponse
    {
        $factoryId = $request->get('factory_id');

        $programNo = KnittingProgram::query()
            ->when($factoryId, Filter::applyFilter('factory_id', $factoryId))
            ->where('production_pending_status', 0)
            ->select('program_no as text', 'program_no as id')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($programNo, Response::HTTP_OK);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getProgramData($id): JsonResponse
    {
        try {
            $knittingProgram = (new ProgramDataService($id))->response();

            return response()->json($knittingProgram, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getKnitCardNo(Request $request): JsonResponse
    {
        $programId = $request->get('program_id');
        $data = KnitCard::query()
            ->when($programId, Filter::applyFilter('knitting_program_id', $programId))
            ->get(['id', 'knit_card_no as text']);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request, KnitCardYarnDetailsStore $knitCardYarnDetailsStore): JsonResponse
    {
        $request->validate([
            'assign_qty' => 'required|numeric|gt:0',
            'production_target_qty' => 'required|numeric|gt:0',
        ]);

        try {
            DB::beginTransaction();
            $id = $request->get('id');
            $yarnDetails = $request->get('yarn_details');
            $knitCard = KnitCard::query()->firstOrNew(['id' => $id]);
            $knitCard->fill($request->all())->save();

            $knitCardYarnDetailsStore->handle($knitCard, $yarnDetails);

            DB::commit();
            return response()->json($knitCard, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($id): JsonResponse
    {
        $knitCard = KnitCard::query()->with(['program.planInfo.order'])->find($id);
        $yarnDetails = KnitCardYarnDetail::query()
            ->with(['yarn_count', 'yarn_composition', 'yarn_type'])
            ->where('knit_card_id', $knitCard->id)
            ->get()->map(function ($yarnDetail) {
                return [
                    'id' => $yarnDetail->id,
                    'knitting_program_id' => $yarnDetail->knitting_program_id,
                    'knit_yarn_allocation_detail_id' => $yarnDetail->knit_yarn_allocation_detail_id,
                    'yarn_count_id' => $yarnDetail->yarn_count_id,
                    'yarn_count_value' => $yarnDetail->yarn_count->yarn_count,
                    'yarn_composition_id' => $yarnDetail->yarn_composition_id,
                    'yarn_composition_value' => $yarnDetail->yarn_composition->yarn_composition,
                    'yarn_type_id' => $yarnDetail->yarn_type_id,
                    'yarn_type_value' => $yarnDetail->yarn_type->name,
                    'yarn_color' => $yarnDetail->yarn_color,
                    'yarn_brand' => $yarnDetail->yarn_brand,
                    'yarn_lot' => $yarnDetail->yarn_lot,
                    'store_id' => $yarnDetail->store_id,
                    'uom_id' => $yarnDetail->uom_id,
                    'vdq' => $yarnDetail->vdq,
                ];
            });

        $knitCard = $this->formatEditData($knitCard);

        return response()->json(['knit_card' => $knitCard, 'yarn_details' => $yarnDetails], Response::HTTP_OK);
    }

    /**
     * @throws Throwable
     */
    public function delete(KnitCard $knitCard): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $knitCard->yarnDetails()->delete();
            $knitCard->delete();
            Session::flash('success', 'Data Deleted Successfully');
            DB::commit();
        } catch (\Exception $e) {
            Session::flash('error', 'Something Went Wrong');
        }

        return back();
    }

    public function formatEditData($knitCard): array
    {
        $program = $knitCard->program;
        $colorQty = KnittingProgramColorsQty::query()->where([
                'knitting_program_id' => $program->id,
                'item_color_id' => $knitCard->color_id
            ])->first()->program_qty ?? 0;

        return [
            'id' => $knitCard->id,
            'factory_id' => $knitCard->factory_id,
            'plan_info_id' => $knitCard->plan_info_id,
            'buyer_id' => $knitCard->buyer_id,
            'knitting_program_id' => $knitCard->knitting_program_id,
            'sales_order_no' => $knitCard->sales_order_no,
            'buyer' => $program->planInfo->buyer_name,
            'knitting_party' => $program->party_name,
            'booking_date' => $knitCard->booking_date,
            'machine_dia' => $program->machine_dia,
            'delivery_date' => null,
            'machine_gg' => $program->machine_gg,
            'booking_no' => $program->booking_no,
            'fabric_description' => $program->planInfo->fabric_description,
            'finish_dia' => $program->finish_fabric_dia,
            'season' => $knitCard->planInfo->order->season->season_name ?? '',
            'fabric_type' => null,
            'gsm' => $knitCard->gsm,
            'color_id' => $knitCard->color_id,
            'color' => $knitCard->color,
            'program_dia' => $program->machine_dia,
            'program_gg' => $program->machine_gg,
            'program_qty' => $colorQty, /* Here Program Qty is Color Qty. */
            'production_target_qty' => $knitCard->production_target_qty,
            'knit_card_date' => $knitCard->knit_card_date,
            'assign_qty' => $knitCard->assign_qty,
            'balance_qty' => $knitCard->balance_qty,
            'remarks' => $knitCard->remarks,
            'program' => $knitCard->program
        ];
    }

    public function view($id)
    {
        $data = KnitCard::query()
            ->where('id', $id)
            ->with(['planInfo', 'program', 'machine', 'yarnDetails.yarn_composition', 'yarnDetails.yarn_count', 'buyer:id,name'])
            ->firstOrFail();

        $yarnDetails = $data->yarnDetails->map(function ($yarn) {
            $referenceNo = YarnReceiveDetail::query()
                ->where(YarnItemAction::itemCriteria($yarn))->get()
                ->pluck('product_code')
                ->implode(', ');

            return [
                'reference_no' => $referenceNo,
                'yarn_count' => $yarn->yarn_count->yarn_count,
                'yarn_type' => $yarn->yarn_type->name,
                'yarn_brand' => $yarn->yarn_brand,
                'yarn_lot' => $yarn->yarn_lot,
                'vdq' => $yarn->vdq,
            ];
        });

        if (request('type') === 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView('knitting::knit-card.pdf',
                compact('data', 'yarnDetails')
            )->setPaper('a4')->setOrientation('portrait')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream($data->program_no . '-knit-card.pdf');
        }

        return view('knitting::knit-card.view', compact('data', 'yarnDetails'));
    }

    public function view2($id)
    {
        $data = KnitCard::query()
            ->where('id', $id)
            ->with([
                'planInfo',
                'program',
                'machine',
                'yarnDetails.yarn_composition',
                'yarnDetails.yarn_count',
                'yarnDetails.yarn_type',
                'knitCardRoll.shift',
                'buyer:id,name'
            ])
            ->firstOrFail();
        $shift = collect($data->knitCardRoll)->pluck('shift.shift_name')->join(', ');
        $fabricSalesOrder = FabricSalesOrder::query()->where('sales_order_no',$data->sales_order_no)->first();
        $date = Carbon::parse($data->created_at)->format('Y-m-d');
        $data->yarnDetails->map(function ($detail){
            $detail['ref_no'] = YarnReceiveDetail::query()->where(YarnItemAction::itemCriteria($detail))->pluck('product_code')->implode(', ');
            return $detail;
        });
//        dd($shift);
        if (request('type') === 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView('knitting::knit-card.pdf-2',
                compact('data','shift','fabricSalesOrder','date')
            )->setPaper('a4')->setOrientation('portrait')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('knit-card.pdf');
        }
//        return $data;
        return view('knitting::knit-card.view-2', compact('data','shift','fabricSalesOrder','date'));
    }
}
