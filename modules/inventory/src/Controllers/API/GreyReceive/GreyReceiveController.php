<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\GreyReceive;

use App\Http\Controllers\Controller;
use DNS1D;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Inventory\Models\GreyReceive\GreyReceive;
use SkylarkSoft\GoRMG\Inventory\Requests\GreyReceiveRequest;
use SkylarkSoft\GoRMG\Inventory\Services\GreyReceive\GreyReceiveFormatterService;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingRollDeliveryChallan;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingRollDeliveryChallanDetail;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class GreyReceiveController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $greyReceives = GreyReceive::query()->when($search, Filter::applyFilter('challan_no', $search))
            ->orderBy('id', 'desc')
            ->paginate();
        return view('inventory::grey-receive.index', compact('greyReceives'));
    }

    public function create()
    {
        return view('inventory::grey-receive.create_update');
    }

    public function fetchChallan(Request $request): JsonResponse
    {
        $factoryId = $request->get('factory_id') ?? null;
        $search = $request->get('search') ?? null;

        $challans = KnittingRollDeliveryChallan::query()
            ->withCount(['challanDetails' => function ($query) {
                return $query->whereNull('received_status');
            }])
            ->where('factory_id', $factoryId)
            ->when($search, function ($query) use ($search) {
                return $query->where('challan_no', 'LIKE', '%' . $search . '%');
            })
            ->limit(30)
            ->get()->filter(function ($item) {
                return $item->challan_details_count > 0;
            })->map(function ($item) {
                return [
                    'knittingRollDeliveryChallanId' => $item['id'],
                    'id' => $item['challan_no'],
                    'text' => $item['challan_no'],
                ];
            })->values();

        return response()->json([
            'data' => $challans
        ]);

    }

    public function store(GreyReceiveRequest $request): JsonResponse
    {
        try {
            $greyReceive = new GreyReceive();
            $barcode = $request->get('barcode');
            $greyReceive->fill(collect($request)->all());
            if ($barcode) {
                $roll = KnittingRollDeliveryChallanDetail::query()->where('knitting_program_roll_id', $barcode)->first();
                if (!$roll) {
                    return response()->json([
                        'msg' => 'No Data Found.',
                        'status' => Response::HTTP_NOT_FOUND
                    ], Response::HTTP_NOT_FOUND);
                }
                $greyReceive->challan_no = $roll->challan_no;
            }
            $greyReceive->save();
            return response()->json($greyReceive);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function show(GreyReceive $greyReceive): JsonResponse
    {
        $data = $greyReceive->load('details');
        $roll = KnittingRollDeliveryChallanDetail::query()->where('challan_no', $data->challan_no)->first();
        $barcode = str_pad($roll->knitting_program_roll_id, 9, '0', STR_PAD_LEFT);
        $data->barcode = $barcode;

        $data->details->map(function ($data) use ($barcode) {
            $data['barcode_view'] = DNS1D::getBarcodeSVG(($barcode), "C128A", 1, 16, '', false);
            return $data;
        });

        return response()->json($data);
    }

    public function fetchChallanDetails(Request $request): JsonResponse
    {
        $challanDetails = KnittingRollDeliveryChallanDetail::query()
            ->with('planningInfo.bodyPart', 'planningInfo.colorType', 'knittingProgram.knittingParty',
                'knittingProgram.yarnRequisition.details.yarn_count',
                'knittingProgram.yarnRequisition.details.composition',
                'factory:id,factory_name', 'knitProgramRoll', 'knitCard.yarnDetails'
            )
            ->where(function ($query) use ($request) {
                $query->where('challan_no', $request->get('challan_no'))
                    ->orWhere('knitting_program_roll_id', (int)$request->get('barcode'));
            })
            ->whereNull('received_status')
            ->get();
        $challanDetails = GreyReceiveFormatterService::formatChallan($challanDetails);
        return response()->json($challanDetails->values());
    }


    /**
     * @throws Throwable
     */
    public function storeDetails(GreyReceive $greyReceive, Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->except('challan_no') as $item) {
                if ($item['id']) {
                    $greyReceive->details()->find($item['id'])->update(collect($item)->all());
                } else {
                    $greyReceive->details()->create(collect($item)->all());
                }
            }

            $challanNo = $request->get('challan_no');
            KnittingRollDeliveryChallanDetail::query()->where('challan_no', $challanNo)->update(['received_status' => 1]);

            DB::commit();
            return response()->json($greyReceive->load('details'));

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }

    public function destroy(GreyReceive $greyReceive, Request $request)
    {
        try {
            DB::beginTransaction();
            $greyReceive->delete();
            $greyReceive->details()->delete();
            $challanNo = $request->get('challan_no');
            KnittingRollDeliveryChallanDetail::query()->where('challan_no', $challanNo)->update(['received_status' => null]);

            DB::commit();
            Session::flash('error', 'Data Deleted Successfully');
            return redirect('/inventory/grey-receive');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('error', 'Something went wrong !');
            return redirect('/inventory/grey-receive');
        }
    }


}

