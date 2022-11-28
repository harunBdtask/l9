<?php


namespace SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingFinishingProduction;

use PDF;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Symfony\Component\HttpFoundation\Response;
use  SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Dyeing\Actions\DyeingFinishingProductionAction;
use SkylarkSoft\GoRMG\Dyeing\Requests\DyeingFinishingProductionRequest;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\DyeingFinishingProductionFormatter;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingFinishingProduction\DyeingFinishingProduction;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingFinishingProduction\DyeingFinishingProductionDetail;

class FinishingProductionController extends Controller
{

    public function index(Request $request)
    {
        $finishingProductions = DyeingFinishingProduction::query()
            ->with([
                'buyer',
                'shift',
                'subDyeingUnit',
                'machine',
                'finishingProductionDetails'
            ])
            ->search($request)
            ->withSum('finishingProductionDetails as total_finish', 'finish_qty')
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_finishing_production.index', [
            'finishingProductions' => $finishingProductions,
            'factories' => $factories,
            'buyers' => $buyers
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_finishing_production.form');
    }

    /**
     * @param DyeingFinishingProductionRequest $request
     * @param DyeingFinishingProduction $dyeingFinishingProduction
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(DyeingFinishingProductionRequest $request,
                          DyeingFinishingProduction        $dyeingFinishingProduction,
                          DyeingFinishingProductionAction  $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingFinishingProduction->fill($request->all())->save();
            $action->storeDetails(
                $dyeingFinishingProduction,
                $request->input('finishing_production_details')
            );
            DB::commit();
            return response()->json([
                'message' => 'Finishing Production Store Successfully',
                'data' => $dyeingFinishingProduction,
                'status' => Response::HTTP_CREATED,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingFinishingProduction $dyeingFinishingProduction
     * @param DyeingFinishingProductionFormatter $formatter
     * @return JsonResponse
     */
    public function edit(DyeingFinishingProduction          $dyeingFinishingProduction,
                         DyeingFinishingProductionFormatter $formatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch Successfully',
                'data' => $formatter->format($dyeingFinishingProduction),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingFinishingProductionAction $action
     * @param DyeingFinishingProductionRequest $request
     * @param DyeingFinishingProduction $dyeingFinishingProduction
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(DyeingFinishingProductionAction  $action,
                           DyeingFinishingProductionRequest $request,
                           DyeingFinishingProduction        $dyeingFinishingProduction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingFinishingProduction->fill($request->all())->save();

            $action->updateDetails(
                $dyeingFinishingProduction,
                $request->input('finishing_production_details')
            );
            DB::commit();
            return response()->json([
                'message' => 'Finishing Production Update Successfully',
                'data' => $dyeingFinishingProduction,
                'status' => Response::HTTP_CREATED,
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(DyeingFinishingProduction $dyeingFinishingProduction)
    {
        try {
            $dyeingFinishingProduction->delete();
            Session::flash('success', 'Finishing Production deleted successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function view($id)
    {
        $finishingProduction = DyeingFinishingProduction::query()
            ->with([
                'buyer',
                'dyeingBatch.machineAllocations.machine',
                'textileOrder',
                'shift',
                'subDyeingUnit',
                'machine',
                'finishingProductionDetails.color',
            ])
            ->where('id', $id)
            ->first();
        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_finishing_production.view', [
            'finishingProduction' => $finishingProduction
        ]);
    }

    public function pdf($id)
    {
        $finishingProduction = DyeingFinishingProduction::query()
            ->with([
                'buyer',
                'dyeingBatch.machineAllocations.machine',
                'textileOrder',
                'shift',
                'subDyeingUnit',
                'machine',
                'finishingProductionDetails.color',
            ])
            ->where('id', $id)
            ->first();

            $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('dyeing::textile_modules.dyeing_finishing_production.pdf', [
                'finishingProduction' => $finishingProduction,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            
            return $pdf->stream("{$id}_Dyeing_finishing_production.pdf");
    }

}
