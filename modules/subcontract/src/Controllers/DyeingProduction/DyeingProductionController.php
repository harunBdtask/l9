<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProduction;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProduction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingProductionRequests\SubDyeingProductionRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubDyeingProductionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DyeingProductionController extends Controller
{
    public function index(Request $request)
    {
        $dyeingProduction = SubDyeingProduction::query()
            ->with([
                'supplier',
                'subDyeingBatch',
                'shift',
                'subDyeingProductionDetails',
            ])
            ->search($request)
            ->orderBy('id', 'desc')
            ->get();

        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select', 0);
        $machines = DyeingMachine::query()->pluck('name', 'id')->prepend('Select', 0);
        $shifts = Shift::query()->pluck('shift_name', 'id')->prepend('Select', 0);
        $suppliers = Buyer::query()->pluck('name', 'id')->prepend('Select', 0);

        return view('subcontract::textile_module.dyeing_production.index', [
            'dyeingProduction' => $dyeingProduction,
            'factories' => $factories,
            'machines' => $machines,
            'shifts' => $shifts,
            'suppliers' => $suppliers,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.dyeing_production.form');
    }

    /**
     * @param SubDyeingBatch $subDyeingBatch
     * @return JsonResponse
     */
    public function getBatchData(SubDyeingBatch $subDyeingBatch): JsonResponse
    {
        try {
            $subDyeingBatch->load(['subDyeingUnit', 'batchDetails', 'machineAllocations.machine']);

            $batchData = (new SubDyeingProductionService())->fetchBatchDetailsData($subDyeingBatch);

            return response()->json([
                'data' => $batchData,
                'message' => 'Fetched Successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingProductionRequest $request
     * @param SubDyeingProduction $subDyeingProduction
     * @return JsonResponse
     */
    public function store(SubDyeingProductionRequest $request, SubDyeingProduction $subDyeingProduction): JsonResponse
    {
        try {
            $subDyeingProduction->fill($request->all())->save();

            $subDyeingProduction->subDyeingProductionDetails()->createMany($request->get('details'));

            return response()->json([
                'data' => $subDyeingProduction,
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
     * @param SubDyeingProduction $subDyeingProduction
     * @return JsonResponse
     */
    public function edit(SubDyeingProduction $subDyeingProduction): JsonResponse
    {
        try {
            $subDyeingProduction->load('supplier', 'subDyeingProductionDetails', 'shift');

            $formattedProduction = (new SubDyeingProductionService())->formatProduction($subDyeingProduction);

            return response()->json([
                'data' => $formattedProduction,
                'message' => 'Production fetched successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingProduction $subDyeingProduction
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function show(SubDyeingProduction $subDyeingProduction)
    {
        $subDyeingProduction->load('subDyeingProductionDetails', 'subDyeingBatch.machineAllocations.machine');

        return view('subcontract::textile_module.dyeing_production.view', [
            'dyeingProduction' => $subDyeingProduction,
        ]);
    }

    /**
     * @param SubDyeingProduction $subDyeingProduction
     * @return mixed
     */
    public function pdf(SubDyeingProduction $subDyeingProduction)
    {
        $subDyeingProduction->load('subDyeingProductionDetails', 'subDyeingBatch.machineAllocations.machine');
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.dyeing_production.pdf', [
                'dyeingProduction' => $subDyeingProduction,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("dyeing_production.pdf");
    }

    /**
     * @param SubDyeingProductionRequest $request
     * @param SubDyeingProduction $subDyeingProduction
     * @return JsonResponse
     */
    public function update(SubDyeingProductionRequest $request, SubDyeingProduction $subDyeingProduction): JsonResponse
    {
        try {
            $subDyeingProduction->fill($request->all())->save();
            $this->updateDetails($request);

            return response()->json([
                'data' => $subDyeingProduction,
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
            SubDyeingProductionDetail::query()->find($detail['id'])->update($detail);
        }
    }

    /**
     * @param SubDyeingProduction $subDyeingProduction
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingProduction $subDyeingProduction): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $subDyeingProduction->subDyeingProductionDetails()->delete();
            $subDyeingProduction->delete();

            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
