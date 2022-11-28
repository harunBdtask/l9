<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingSqueezer;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingSqueezer;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingSqueezerDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingTubeCompactingDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingSqueezerRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDyeingSqueezerController extends Controller
{
    public function index(Request $request)
    {
        $subDyeingSqueezers = SubDyeingSqueezer::query()
            ->with([
                'subDyeingSqueezerDetail',
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

        return view('subcontract::textile_module.squeezer.index', [
            'squeezers' => $subDyeingSqueezers,
            'factories' => $factories,
            'dyeingUnits' => $dyeingUnits,
            'machines' => $machines,
            'shifts' => $shifts,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.squeezer.form');
    }

    /**
     * @param SubDyeingSqueezerRequest $request
     * @param SubDyeingSqueezer $subDyeingSqueezer
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubDyeingSqueezerRequest $request, SubDyeingSqueezer $subDyeingSqueezer): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingSqueezer->fill($request->all())->save();

            $subDyeingSqueezer->subDyeingSqueezerDetail()->createMany($request->get('details'));
            DB::commit();

            return response()->json([
                'data' => $subDyeingSqueezer,
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
     * @param SubDyeingSqueezer $subDyeingSqueezer
     * @return JsonResponse
     */
    public function edit(SubDyeingSqueezer $subDyeingSqueezer): JsonResponse
    {
        try {
            $subDyeingSqueezer->load([
                'supplier',
                'subTextileOrder',
                'subDyeingBatch',
                'subDyeingUnit',
                'subDyeingSqueezerDetail.subTextileOrderDetail',
                'subDyeingSqueezerDetail.subDyeingBatchDetail',
                'shift',
            ]);

            $subDyeingSqueezer->factory_name = $subDyeingSqueezer->factory->factory_name;
            $subDyeingSqueezer->supplier_name = $subDyeingSqueezer->supplier->name;

            $subDyeingSqueezer['details'] = $subDyeingSqueezer->getRelation('subDyeingSqueezerDetail')
                ->map(function ($detail) {
                    $prevQty = SubDyeingSqueezerDetail::query()
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
                'data' => $subDyeingSqueezer,
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
     * @param SubDyeingSqueezerRequest $request
     * @param SubDyeingSqueezer $subDyeingSqueezer
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubDyeingSqueezerRequest $request, SubDyeingSqueezer $subDyeingSqueezer): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingSqueezer->fill($request->all())->save();
            $this->updateDetails($request);
            DB::commit();

            return response()->json([
                'data' => $subDyeingSqueezer,
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
     * @param SubDyeingSqueezer $subDyeingSqueezer
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingSqueezer $subDyeingSqueezer): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingSqueezer->subDyeingSqueezerDetail()->delete();
            $subDyeingSqueezer->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
