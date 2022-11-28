<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Compactor;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubCompactor;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubCompactorDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubCompactorRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CompactorController extends Controller
{
    public function index(Request $request)
    {
        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $shifts = Shift::query()
            ->pluck('shift_name', 'id')
            ->prepend('Select', 0);

        $compactors = SubCompactor::query()
            ->with([
                'subCompactorDetails',
                'supplier',
                'subDyeingBatch',
                'shift',
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        return view('subcontract::textile_module.compactor.index', [
            'factories' => $factories,
            'shifts' => $shifts,
            'compactors' => $compactors,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.compactor.form');
    }

    /**
     * @param SubCompactorRequest $request
     * @param SubCompactor $subCompactor
     * @return JsonResponse
     */
    public function store(SubCompactorRequest $request, SubCompactor $subCompactor): JsonResponse
    {
        try {
            $subCompactor->fill($request->all())->save();

            $subCompactor->subCompactorDetails()->createMany($request->get('details'));

            return response()->json([
                'data' => $subCompactor,
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
     * @param SubCompactor $subCompactor
     * @return JsonResponse
     */
    public function edit(SubCompactor $subCompactor): JsonResponse
    {
        try {
            $subCompactor->load([
                'supplier',
                'subTextileOrder',
                'subDyeingBatch',
                'subDyeingUnit',
                'subCompactorDetails.subTextileOrderDetail',
                'subCompactorDetails.subDyeingBatchDetail',
                'shift',
            ]);

            $subCompactor->factory_name = $subCompactor->factory->factory_name;
            $subCompactor->supplier_name = $subCompactor->supplier->name;

            $subCompactor['details'] = $subCompactor->getRelation('subCompactorDetails')->map(function ($detail) {
                $prevQty = SubCompactorDetail::query()
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
                'data' => $subCompactor,
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
     * @param SubCompactorRequest $request
     * @param SubCompactor $subCompactor
     * @return JsonResponse
     */
    public function update(SubCompactorRequest $request, SubCompactor $subCompactor): JsonResponse
    {
        try {
            $subCompactor->fill($request->all())->save();
            $this->updateDetails($request);

            return response()->json([
                'data' => $subCompactor,
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
            SubCompactorDetail::query()->find($detail['id'])->update($detail);
        }
    }

    /**
     * @param SubCompactor $subCompactor
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubCompactor $subCompactor): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subCompactor->subCompactorDetails()->delete();
            $subCompactor->delete();
            DB::commit();

            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
