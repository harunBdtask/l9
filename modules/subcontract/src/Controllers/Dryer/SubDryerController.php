<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Dryer;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDryer;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDryerDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDryerRequests;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDryerController extends Controller
{
    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $subDryers = SubDryer::query()
            ->with([
                'subDryerDetails',
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

        return view('subcontract::textile_module.dryer.index', [
            'subDryers' => $subDryers,
            'factories' => $factories,
            'dyeingUnits' => $dyeingUnits,
            'machines' => $machines,
            'shifts' => $shifts,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.dryer.form');
    }

    /**
     * @param SubDryerRequests $request
     * @param SubDryer $subDryer
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubDryerRequests $request, SubDryer $subDryer): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDryer->fill($request->all())->save();

            $subDryer->subDryerDetails()->createMany($request->get('details'));
            DB::commit();

            return response()->json([
                'data' => $subDryer,
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
     * @param SubDryer $subDryer
     * @return JsonResponse
     */
    public function edit(SubDryer $subDryer): JsonResponse
    {
        try {
            $subDryer->load([
                'supplier',
                'subTextileOrder',
                'subDyeingBatch',
                'subDyeingUnit',
                'subDryerDetails.subTextileOrderDetail',
                'subDryerDetails.subDyeingBatchDetail',
                'shift',
            ]);

            $subDryer->factory_name = $subDryer->factory->factory_name;
            $subDryer->supplier_name = $subDryer->supplier->name;

            $subDryer['details'] = $subDryer->getRelation('subDryerDetails')->map(function ($detail) {
                $prevQty = SubDryerDetail::query()
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
                'data' => $subDryer,
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
     * @param SubDryerRequests $request
     * @param SubDryer $subDryer
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubDryerRequests $request, SubDryer $subDryer): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subDryer->fill($request->all())->save();
            $this->updateDetails($request);
            DB::commit();

            return response()->json([
                'data' => $subDryer,
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
            SubDryerDetail::query()->find($detail['id'])->update($detail);
        }
    }

    /**
     * @param SubDryer $subDryer
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDryer $subDryer): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subDryer->subDryerDetails()->delete();
            $subDryer->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
