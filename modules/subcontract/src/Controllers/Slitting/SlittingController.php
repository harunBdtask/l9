<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Slitting;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubSlitting;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubSlittingDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubSlittingRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SlittingController extends Controller
{
    public function index(Request $request)
    {
        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $shifts = Shift::query()
            ->pluck('shift_name', 'id')
            ->prepend('Select', 0);

        $slittings = SubSlitting::query()
            ->with([
                'subSlittingDetails',
                'supplier',
                'subDyeingBatch',
                'shift',
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        return view('subcontract::textile_module.slitting.index', [
            'shifts' => $shifts,
            'factories' => $factories,
            'slittings' => $slittings,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.slitting.form');
    }

    /**
     * @param SubSlittingRequest $request
     * @param SubSlitting $subSlitting
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubSlittingRequest $request, SubSlitting $subSlitting): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subSlitting->fill($request->all())->save();

            $subSlitting->subSlittingDetails()->createMany($request->get('details'));
            DB::commit();

            return response()->json([
                'data' => $subSlitting,
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
     * @param SubSlitting $subSlitting
     * @return JsonResponse
     */
    public function edit(SubSlitting $subSlitting): JsonResponse
    {
        try {
            $subSlitting->load([
                'supplier',
                'subTextileOrder',
                'subDyeingBatch',
                'subDyeingUnit',
                'subSlittingDetails.subTextileOrderDetail',
                'subSlittingDetails.subDyeingBatchDetail',
                'shift',
            ]);

            $subSlitting->factory_name = $subSlitting->factory->factory_name;
            $subSlitting->supplier_name = $subSlitting->supplier->name;

            $subSlitting['details'] = $subSlitting->getRelation('subSlittingDetails')->map(function ($detail) {
                $prevQty = SubSlittingDetail::query()
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
                'data' => $subSlitting,
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
     * @param SubSlittingRequest $request
     * @param SubSlitting $subSlitting
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubSlittingRequest $request, SubSlitting $subSlitting): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subSlitting->fill($request->all())->save();
            $this->updateDetails($request);
            DB::commit();

            return response()->json([
                'data' => $subSlitting,
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
            SubSlittingDetail::query()->find($detail['id'])->update($detail);
        }
    }

    /**
     * @param SubSlitting $subSlitting
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubSlitting $subSlitting): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subSlitting->subSlittingDetails()->delete();
            $subSlitting->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}