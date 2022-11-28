<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingTubeCompacting;

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
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingTubeCompacting;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingTubeCompactingDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingTubeCompactingRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDyeingTubeCompactingController extends Controller
{
    public function index(Request $request)
    {
        $subDyeingTubeCompacting = SubDyeingTubeCompacting::query()
            ->with([
                'subDyeingTubeCompactingDetail',
                'shift',
                'machine',
                'subDyeingUnit',
                'supplier',
                'subTextileOrder',
                'subDyeingBatch',
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select', 0);
        $dyeingUnits = SubDyeingUnit::query()->pluck('name', 'id')->prepend('Select', 0);
        $machines = DyeingMachine::query()->pluck('name', 'id')->prepend('Select', 0);
        $shifts = Shift::query()->pluck('shift_name', 'id')->prepend('Select', 0);

        return view('subcontract::textile_module.tube-compacting.index', [
            'subDyeingTubeCompacting' => $subDyeingTubeCompacting,
            'factories' => $factories,
            'dyeingUnits' => $dyeingUnits,
            'machines' => $machines,
            'shifts' => $shifts,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.tube-compacting.form');
    }

    /**
     * @param SubDyeingTubeCompactingRequest $request
     * @param SubDyeingTubeCompacting $subDyeingTubeCompacting
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubDyeingTubeCompactingRequest $request, SubDyeingTubeCompacting $subDyeingTubeCompacting): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingTubeCompacting->fill($request->all())->save();

            $subDyeingTubeCompacting->subDyeingTubeCompactingDetail()->createMany($request->get('details'));
            DB::commit();

            return response()->json([
                'data' => $subDyeingTubeCompacting,
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
     * @param SubDyeingTubeCompacting $subDyeingTubeCompacting
     * @return JsonResponse
     */
    public function edit(SubDyeingTubeCompacting $subDyeingTubeCompacting): JsonResponse
    {
        try {
            $subDyeingTubeCompacting->load([
                'supplier',
                'subTextileOrder',
                'subDyeingBatch',
                'subDyeingUnit',
                'subDyeingTubeCompactingDetail.subTextileOrderDetail',
                'subDyeingTubeCompactingDetail.subDyeingBatchDetail',
                'shift',
            ]);

            $subDyeingTubeCompacting->factory_name = $subDyeingTubeCompacting->factory->factory_name;
            $subDyeingTubeCompacting->supplier_name = $subDyeingTubeCompacting->supplier->name;

            $subDyeingTubeCompacting['details'] = $subDyeingTubeCompacting->getRelation('subDyeingTubeCompactingDetail')
                ->map(function ($detail) {
                    $prevQty = SubDyeingTubeCompactingDetail::query()
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
                'data' => $subDyeingTubeCompacting,
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
     * @param SubDyeingTubeCompactingRequest $request
     * @param SubDyeingTubeCompacting $subDyeingTubeCompacting
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubDyeingTubeCompactingRequest $request, SubDyeingTubeCompacting $subDyeingTubeCompacting): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingTubeCompacting->fill($request->all())->save();
            $this->updateDetails($request);
            DB::commit();

            return response()->json([
                'data' => $subDyeingTubeCompacting,
                'message' => 'Data updated successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function updateDetails(Request $request)
    {
        $detailsForm = $request->get('details');

        foreach ($detailsForm as $detail) {
            SubDyeingTubeCompactingDetail::query()->find($detail['id'])->update($detail);
        }
    }

    /**
     * @param SubDyeingTubeCompacting $subDyeingTubeCompacting
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingTubeCompacting $subDyeingTubeCompacting): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingTubeCompacting->subDyeingTubeCompactingDetail()->delete();
            $subDyeingTubeCompacting->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
