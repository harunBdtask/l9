<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\SubDyeingHtSet;

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
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingHtSet;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingHtSetDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingHtSetRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDyeingHtSetController extends Controller
{
    public function index(Request $request)
    {
        $subDyeingHtSets = SubDyeingHtSet::query()
            ->with([
                'subDyeingHtSetDetail',
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

        return view('subcontract::textile_module.ht-set.index', [
            'subDyeingHtSets' => $subDyeingHtSets,
            'factories' => $factories,
            'dyeingUnits' => $dyeingUnits,
            'machines' => $machines,
            'shifts' => $shifts,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.ht-set.form');
    }

    /**
     * @param SubDyeingHtSetRequest $request
     * @param SubDyeingHtSet $subDyeingHtSet
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubDyeingHtSetRequest $request, SubDyeingHtSet $subDyeingHtSet): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingHtSet->fill($request->all())->save();

            $subDyeingHtSet->subDyeingHtSetDetail()->createMany($request->get('details'));
            DB::commit();

            return response()->json([
                'data' => $subDyeingHtSet,
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
     * @param SubDyeingHtSet $subDyeingHtSet
     * @return JsonResponse
     */
    public function edit(SubDyeingHtSet $subDyeingHtSet): JsonResponse
    {
        try {
            $subDyeingHtSet->load([
                'supplier',
                'subTextileOrder',
                'subDyeingBatch',
                'subDyeingUnit',
                'subDyeingHtSetDetail.subTextileOrderDetail',
                'subDyeingHtSetDetail.subDyeingBatchDetail',
                'shift',
            ]);

            $subDyeingHtSet->factory_name = $subDyeingHtSet->factory->factory_name;
            $subDyeingHtSet->supplier_name = $subDyeingHtSet->supplier->name;

            $subDyeingHtSet['details'] = $subDyeingHtSet->getRelation('subDyeingHtSetDetail')
                ->map(function ($detail) {
                    $prevQty = SubDyeingHtSetDetail::query()
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
                'data' => $subDyeingHtSet,
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
     * @param SubDyeingHtSetRequest $request
     * @param SubDyeingHtSet $subDyeingHtSet
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubDyeingHtSetRequest $request, SubDyeingHtSet $subDyeingHtSet): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingHtSet->fill($request->all())->save();
            $this->updateDetails($request);
            DB::commit();

            return response()->json([
                'data' => $subDyeingHtSet,
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
            SubDyeingHtSetDetail::query()->find($detail['id'])->update($detail);
        }
    }

    /**
     * @param SubDyeingHtSet $subDyeingHtSet
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingHtSet $subDyeingHtSet): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingHtSet->subDyeingHtSetDetail()->delete();
            $subDyeingHtSet->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
