<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingRollDeliveryChallan;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingRollDeliveryChallanDetail;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\ProductionVariableSettingsController;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RollWiseFabricDeliveryController extends Controller
{
    public function index()
    {
        $challanList = KnittingRollDeliveryChallan::query()
            ->orderBy('id', 'desc')
            ->paginate();
        //TODO REFACTOR;
        $dashboardOverview = [
            'Not Started' => 0,
            'In Progress' => 0,
            'On Hold' => 0,
            'Cancelled' => 0,
            'Finished' => 0
        ];

        return view('knitting::roll-wise-fabric-delivery.index', [
            'challan_list' => $challanList, 'dashboardOverview' => $dashboardOverview
        ]);
    }

    public function create()
    {
        $factories = Factory::query()->get();
        $challanNo = $this->getRunningChallanNo() ?? time() . userId();
        $challanList = $this->getRunningChallanList($challanNo);
        $challan_date = date('Y-m-d');
        return view('knitting::roll-wise-fabric-delivery.create', [
            'factories' => $factories,
            'challan_no' => $challanNo,
            'challan_list' => $challanList,
            'challan_date' => $challan_date,
            'form_mode' => 'Create',
        ]);
    }

    public function searchDeliverableRolls(Request $request): JsonResponse
    {
        try {
            $factoryId = $request->get('factory_id');
            $buyerName = $request->get('buyer_id');
            $programId = $request->get('program_id');
            $knitCardId = $request->get('knit_card_id');
            $styleName = $request->get('style_name');
            $uniqueId = $request->get('unique_id');
            $poNo = $request->get('po_no');
            $bookingNo = $request->get('booking_no');
            $rollNo = $request->get('roll_no');
            $type = $request->get('type');
            $from_date = $request->from_date ?? null;
            $to_date = $request->to_date ?? null;
            $id = $rollNo ? substr($rollNo, 1, 9) : null;

            if (!$factoryId) {
                $data = null;
            } else {

                $productionVariable = (new ProductionVariableSettingsController())->getProductionVariableSetting($factoryId, false)['knitting_qc_maintain'] ?? 'no';

                $data = KnitProgramRoll::query()
                    ->with(['planningInfo.bodyPart', 'planningInfo.colorType', 'knitCard'])
                    ->where('delivery_status', 0)
                    ->when($id, function ($query) use ($id) {
                        $query->where('id', $id);
                    })
                    ->when(!$id, function ($query) use ($factoryId) {
                        $query->where('factory_id', $factoryId);
                    })
                    ->when($productionVariable == 'yes' && !$id, function ($query) {
                        $query->whereNotNull('qc_status');
                    })
                    ->when($programId && !$id, function ($query) use ($programId) {
                        $query->where('knitting_program_id', $programId);
                    })
                    ->when($knitCardId && !$id, function ($query) use ($knitCardId) {
                        $query->where('knit_card_id', $knitCardId);
                    })
                    ->when($buyerName && !$id, function ($query) use ($buyerName) {
                        $query->whereHas('knitCard', function ($q) use ($buyerName) {
                            $q->where('buyer_id', $buyerName);
                        });
                    })
                    ->when($styleName && !$id, function ($query) use ($styleName) {
                        $query->whereHas('planningInfo', function ($q) use ($styleName) {
                            $q->where('style_name', $styleName);
                        });
                    })
                    ->when($uniqueId && !$id, function ($query) use ($uniqueId) {
                        $query->whereHas('planningInfo', function ($q) use ($uniqueId) {
                            $q->where('unique_id', $uniqueId);
                        });
                    })
                    ->when($poNo && !$id, function ($query) use ($poNo) {
                        $query->whereHas('planningInfo', function ($q) use ($poNo) {
                            $q->where('po_no', $poNo);
                        });
                    })
                    ->when($bookingNo && !$id, function ($query) use ($bookingNo) {
                        $query->whereHas('planningInfo', function ($q) use ($bookingNo) {
                            $q->where('booking_no', $bookingNo);
                        });
                    })
                    ->when($type && !$id, function ($query) use ($type) {
                        $query->whereHas('planningInfo', function ($q) use ($type) {
                            $q->where('booking_type', $type);
                        });
                    })
                    ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                        $query->whereDate('production_datetime', '>=', $from_date)
                            ->whereDate('production_datetime', '<=', $to_date);
                    })
                    ->paginate(20);
            }

            $view = view('knitting::roll-wise-fabric-delivery.table.deliverable_rolls', [
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

    public function edit($challanNo)
    {
        try {
            $factories = Factory::query()->get();
            $challanList = $this->getRunningChallanList($challanNo);
            $challan = $challanList->first()->challan;
            return view('knitting::roll-wise-fabric-delivery.create', [
                'factories' => $factories,
                'challan_no' => $challanNo,
                'challan_list' => $challanList,
                'challan_date' => $challan->challan_date ?? date('Y-m-d'),
                'destination' => $challan->destination,
                'driver_name' => $challan->driver_name,
                'vehicle_no' => $challan->vehicle_no,
                'remarks' => $challan->remarks,
                'form_mode' => 'Update',
            ]);
        } catch (Exception $e) {
            Session::flash('alert-danger', SOMETHING_WENT_WRONG);
            return redirect()->back();
        }
    }

    public function getRunningChallanNo()
    {
        return Cache::remember('knit_roll_delivery_challan_' . userId(), 1440, function () {
            $challan = KnittingRollDeliveryChallanDetail::query()
                ->where('challan_status', 0)
                ->first();
            return $challan ? $challan->challan_no : null;
        });
    }

    public function getRunningChallanList($challanNo)
    {
        return KnittingRollDeliveryChallanDetail::query()
            ->where('challan_no', $challanNo)
            ->get();
    }

    /**
     * @throws Throwable
     */
    public function save($challan_no, Request $request): JsonResponse
    {
        try {
            $request->validate([
                'challan_no' => 'required'
            ], [
                'required' => 'Challan No is Required'
            ]);
            DB::beginTransaction();
            $message = "Challan Updated Successfully!";
            $delivery_qty = KnittingRollDeliveryChallanDetail::query()->select('knitting_program_roll_id')->where('challan_no', $challan_no)->get()->sum('knitProgramRoll.qc_roll_weight');
            $challan = KnittingRollDeliveryChallan::query()
                ->firstOrNew([
                    'challan_no' => $challan_no
                ]);
            $challan->challan_no = $challan_no;
            $challan->challan_date = $request->challan_date ?? date('Y-m-d');
            $challan->delivery_qty = $delivery_qty;
            $challan->destination = $request->get('destination');
            $challan->driver_name = $request->get('driver_name');
            $challan->vehicle_no = $request->get('vehicle_no');
            $challan->remarks = $request->get('remarks');
            $challan->save();

            KnittingRollDeliveryChallanDetail::query()
                ->where('challan_no', $challan_no)
                ->update([
                    'challan_status' => 1
                ]);
            Cache::forget('knit_roll_delivery_challan_' . userId());
            DB::commit();
            $responseStatus = Response::HTTP_OK;
        } catch (Exception $e) {
            DB::rollBack();
            $errors = $e->getMessage();
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = SOMETHING_WENT_WRONG;
        }

        return response()->json([
            'status' => $responseStatus,
            'errors' => $errors ?? null,
            'message' => $message,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function delete($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $challan = KnittingRollDeliveryChallan::query()->findOrFail($id);
            $challan_no = $challan->challan_no;
            $challan->challanDetails->each(function ($item, $key) {
                KnitProgramRoll::query()
                    ->where('id', $item->knitting_program_roll_id)
                    ->update([
                        'delivery_status' => 0,
                        'delivery_challan_no' => null,
                    ]);
            });
            KnittingRollDeliveryChallanDetail::query()->where('challan_no', $challan_no)
                ->update([
                    'deleted_at' => now(),
                    'deleted_by' => userId()
                ]);
            $challan->delete();
            DB::commit();
            Session::flash('alert-success', S_DEL_MSG);
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            $errors = $e->getMessage();
            Session::flash('alert-success', SOMETHING_WENT_WRONG);
            return redirect()->back();
        }
    }

    /**
     * @throws Throwable
     */
    public function detailStore(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $message = "Data Stored Successfully!";

            foreach ($request->get('data') as $key => $value) {
                $existingRoll = KnittingRollDeliveryChallanDetail::query()->where([
                    'knitting_program_roll_id' => $value['knitting_program_roll_id']
                ])->first();

                if ($existingRoll) continue;

                KnittingRollDeliveryChallanDetail::query()->create($value);

                KnitProgramRoll::query()->where('id', $value['knitting_program_roll_id'])
                    ->update([
                        'delivery_status' => 1,
                        'delivery_challan_no' => $value['challan_no'],
                    ]);
            }

            $view = view('knitting::roll-wise-fabric-delivery.table.single_roll_to_challan', [
                'data' => KnittingRollDeliveryChallanDetail::with('knitProgramRoll')->get()
            ])->render();
            $responseStatus = Response::HTTP_OK;

            DB::commit();
        } catch (Exception $e) {
            $errors = $e->getMessage();
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = SOMETHING_WENT_WRONG;
        }

        return response()->json([
            'status' => $responseStatus,
            'errors' => $errors ?? null,
            'view' => $view ?? null,
            'message' => $message,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function detailDelete($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $message = "Deleted Successfully!";
            $challanDetail = KnittingRollDeliveryChallanDetail::query()->findOrFail($id);
            $rollId = $challanDetail->knitting_program_roll_id;
            $challanDetail->forceDelete();
            KnitProgramRoll::query()->where('id', $rollId)
                ->update([
                    'delivery_status' => 0,
                    'delivery_challan_no' => null,
                ]);
            DB::commit();
            $responseStatus = Response::HTTP_OK;
        } catch (Exception $e) {
            DB::rollBack();
            $errors = $e->getMessage();
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = SOMETHING_WENT_WRONG;
        }

        return response()->json([
            'status' => $responseStatus,
            'errors' => $errors ?? null,
            'message' => $message,
        ]);
    }
}
