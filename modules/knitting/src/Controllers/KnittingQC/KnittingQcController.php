<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\KnittingQC;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use SkylarkSoft\GoRMG\Knitting\Actions\KnittingQC\KnitCardRollQCAction;
use SkylarkSoft\GoRMG\Knitting\Models\KnitCard;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Requests\KnittingQcRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnitFabricFaultSetting;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class KnittingQcController extends Controller
{
    public function index(): View
    {
        $factoryOptions = Factory::query()->where('id', factoryId())->pluck('factory_name', 'id');
        $knittingSources = KnittingProgram::KnittingSources;
        $qcPendingStatusOptions = [
            0 => 'Pending',
            1 => 'Done',
        ];
        return view('knitting::knitting-qc.index', [
            'factory_options' => $factoryOptions,
            'knitting_sources' => $knittingSources,
            'qc_pending_status_options' => $qcPendingStatusOptions,
        ]);
    }

    public function knitQcSearch(Request $request): JsonResponse
    {
        try {
            $factoryId = $request->get('factory_id');
            $knittingSourceId = $request->get('knitting_source_id');
            $knittingSource = $request->get('knitting_source_id') && array_key_exists($knittingSourceId, KnittingProgram::KnittingSources)
                ? KnittingProgram::KnittingSources[$knittingSourceId]
                : KnittingProgram::KnittingSources[1];
            $knittingParty = $knittingSource == 'Inhouse' ? 'Book. Company' : 'Knitting Party';
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $programId = $request->get('program_id');
            $rollNo = $request->get('roll_no');
            $type = $request->get('type');
            $roll_id = $rollNo ? substr($rollNo, 1, 9) : null;
            $qcPendingStatus = $request->get('qc_pending_status');

            if ($roll_id) {
                $data = KnitProgramRoll::query()
                    ->where('delivery_status', 0)
                    ->when($roll_id, function ($query) use ($roll_id) {
                        $query->where('id', $roll_id);
                    })
                    ->get();

                $data_source = "rolls";
                $view = view('knitting::knitting-qc.includes.knitting_qc_searched_rolls', [
                    'data' => $data
                ])->render();

            } elseif ($factoryId) {
                $data = KnitCard::query()
                    ->with(['planInfo', 'program', 'knitCardRollWithoutDelivered'])
                    ->has('knitCardRollWithoutDelivered')
                    ->where('factory_id', $factoryId)
                    ->when($programId, function ($query) use ($programId) {
                        $query->where('knitting_program_id', $programId);
                    })
                    ->when($qcPendingStatus, function ($query) use ($qcPendingStatus) {
                        $query->where('qc_pending_status', $qcPendingStatus);
                    })
                    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                        $query->whereDate('knit_card_date', '>=', $fromDate)
                            ->whereDate('knit_card_date', '<=', $toDate);
                    })
                    ->when($type, function ($query) use ($type) {
                        $query->whereHas('planInfo', function ($query) use ($type) {
                            $query->where('booking_type', $type);
                        });
                    })
                    ->latest()
                    ->paginate(20);

                $data_source = "programs";
                $view = view('knitting::knitting-qc.tables.kniting_qc_searched_knitcard_table', [
                    'data' => $data,
                ])->render();
            }

            $responseStatus = Response::HTTP_OK;
            $message = SUCCESS_MSG;
        } catch (Exception $e) {
            $errors = $e->getMessage();
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = SOMETHING_WENT_WRONG;
        }

        return response()->json([
            'view' => $view ?? null,
            'data' => [
                'knitting_source' => $knittingSource,
                'knitting_party' => $knittingParty,
            ],
            'data_source' => $data_source ?? null,
            'status' => $responseStatus,
            'errors' => $errors ?? null,
            'message' => $message,
        ]);
    }

    public function knitQcRollView(Request $request): JsonResponse
    {
        try {
            $knitCardId = $request->get('knit_card_id');
            $data = KnitProgramRoll::query()
                ->where([
                    'delivery_status' => 0,
                    'knit_card_id' => $knitCardId
                ])->get();

            $view = view('knitting::knitting-qc.includes.knitting_qc_searched_rolls', [
                'data' => $data
            ])->render();

            $responseStatus = Response::HTTP_OK;
            $message = SUCCESS_MSG;
        } catch (Exception $e) {
            $errors = $e->getMessage();
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = SOMETHING_WENT_WRONG;
        }

        return response()->json([
            'view' => $view ?? null,
            'status' => $responseStatus,
            'errors' => $errors ?? null,
            'message' => $message,
        ]);
    }

    public function generateForm(Request $request)
    {
        try {
            return view('knitting::knitting-qc.forms.knitting_qc_form');
        } catch (Exception $e) {
            Session::flash('alert-danger', SOMETHING_WENT_WRONG);
            return \redirect('/knitting/knitting-qc');
        }
    }

    public function getKnitQcFormData($roll_id): JsonResponse
    {
        try {
            $knitProgramRoll = KnitProgramRoll::query()->findOrFail($roll_id);

            $knitProgramRoll['point_calculation_method'] = !empty($knitProgramRoll->point_calculation_method) ?
                $knitProgramRoll->point_calculation_method : 1;


            $knitProgramRoll['qc_roll_weight'] = !empty($knitProgramRoll->qc_roll_weight) ?
                $knitProgramRoll->qc_roll_weight : $knitProgramRoll->roll_weight;

            $knitProgramRoll['qc_datetime'] = !empty($knitProgramRoll->qc_datetime) ?
                $knitProgramRoll->qc_datetime : now()->toDateTimeString();

            $existingFaultDetails = $knitProgramRoll->qc_fault_details ?? null;

            $knitProgramRoll['qc_fault_details'] = KnitFabricFaultSetting::query()
                ->where('status', ACTIVE)
                ->orderBy('sequence')
                ->get()
                ->map(function ($collection) use ($existingFaultDetails) {
                    $faultValue = collect($existingFaultDetails)
                        ->where('fault_id', $collection->id)
                        ->first();

                    return [
                        "fault_id" => $collection->id,
                        "fault_name" => $collection->name,
                        "fault_value" => $faultValue['fault_value'] ?? null,
                        "sequence" => $collection->sequence,
                    ];
                });

            $message = SUCCESS;
            $response = Response::HTTP_OK;
        } catch (Exception $e) {
            $message = SOMETHING_WENT_WRONG;
            $errors = $e->getMessage();
            $response = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'knit_program_roll' => $knitProgramRoll ?? null,
            'errors' => $errors ?? null,
            'message' => $message,
        ], $response);
    }

    /**
     * @throws Throwable
     */
    public function save(KnitProgramRoll $knitProgramRoll, KnittingQcRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $knitProgramRoll->fill($request->all());
            $knitProgramRoll->save();

            KnitCardRollQCAction::handle($request->get('knit_card_id'));

            DB::commit();
            $responseStatus = Response::HTTP_OK;
            $message = S_UPDATE_MSG;
        } catch (Exception $e) {
            DB::rollBack();
            $message = SOMETHING_WENT_WRONG;
            $errors = $e->getMessage();
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'data' => $knitProgramRoll,
            'status' => $responseStatus,
            'message' => $message,
            'errors' => $errors ?? null,
        ]);
    }
}
