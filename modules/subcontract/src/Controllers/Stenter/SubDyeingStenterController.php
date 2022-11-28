<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Stenter;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingStentering;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingStenteringDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingStenteringRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDyeingStenterController extends Controller
{
    public function index(Request $request)
    {
        $subDyeingStenterings = SubDyeingStentering::query()
            ->with([
                'factory',
                'supplier',
                'subDyeingStenteringDetails',
                'subDyeingUnit',
                'machine',
                'shift',
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select', 0);
        $dyeingUnits = SubDyeingUnit::query()->pluck('name', 'id')->prepend('Select', 0);
        $machines = DyeingMachine::query()->pluck('name', 'id')->prepend('Select', 0);
        $shifts = Shift::query()->pluck('shift_name', 'id')->prepend('Select', 0);

        return view('subcontract::textile_module.stenter.index', [
            'subDyeingStenterings' => $subDyeingStenterings,
            'factories' => $factories,
            'dyeingUnits' => $dyeingUnits,
            'machines' => $machines,
            'shifts' => $shifts,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.stenter.form');
    }

    /**
     * @param SubDyeingStenteringRequest $request
     * @param SubDyeingStentering $subDyeingStentering
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubDyeingStenteringRequest $request, SubDyeingStentering $subDyeingStentering): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingStentering->fill($request->all())->save();

            $subDyeingStentering->subDyeingStenteringDetails()->createMany($request->get('details'));
            DB::commit();

            return response()->json([
                'data' => $subDyeingStentering,
                'message' => 'Data stored successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingStentering $subDyeingStentering
     * @return JsonResponse
     */
    public function edit(SubDyeingStentering $subDyeingStentering): JsonResponse
    {
        try {
            $subDyeingStentering->load([
                'supplier',
                'subTextileOrder',
                'subDyeingBatch',
                'subDyeingUnit',
                'subDyeingStenteringDetails.subTextileOrderDetail',
                'subDyeingStenteringDetails.subDyeingBatchDetail',
                'shift',
            ]);

            $subDyeingStentering->factory_name = $subDyeingStentering->factory->factory_name;
            $subDyeingStentering->supplier_name = $subDyeingStentering->supplier->name;

            $subDyeingStentering['details'] = $subDyeingStentering
                ->getRelation('subDyeingStenteringDetails')->map(function ($detail) {
                    $prevQty = SubDyeingStenteringDetail::query()
                        ->selectRaw('SUM(fin_no_of_roll) AS totalFinRoll,SUM(finish_qty) AS totalFinQty')
                        ->when($detail->order_details_id, Filter::applyFilter('order_details_id', $detail->order_details_id))
                        ->when($detail->batch_details_id, Filter::applyFilter('batch_details_id', $detail->batch_details_id))
                        ->where('id', '!=', $detail->id)
                        ->first();

                    $detail['color_name'] = $detail->color['name'];
                    $detail['prev_fin_no_of_roll'] = $prevQty->totalFinRoll ?? null;
                    $detail['prev_finish_qty'] = $prevQty->totalFinQty ?? null;
                    $detail['order_qty'] = $detail->subTextileOrderDetail->order_qty ?? null;
                    $detail['batch_qty'] = $detail->subDyeingBatchDetail->issue_qty ?? null;

                    return $detail;
                });

            return response()->json([
                'data' => $subDyeingStentering,
                'message' => 'Data stored successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingStenteringRequest $request
     * @param SubDyeingStentering $subDyeingStentering
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubDyeingStenteringRequest $request, SubDyeingStentering $subDyeingStentering): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingStentering->fill($request->all())->save();
            $this->updateDetails($request);
            DB::commit();

            return response()->json([
                'data' => $subDyeingStentering,
                'message' => 'Data updated successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateDetails(Request $request)
    {
        $detailsForm = $request->get('details');

        foreach ($detailsForm as $detail) {
            SubDyeingStenteringDetail::query()->find($detail['id'])->update($detail);
        }
    }

    /**
     * @param SubDyeingStentering $subDyeingStentering
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingStentering $subDyeingStentering): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingStentering->subDyeingStenteringDetails()->delete();
            $subDyeingStentering->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
