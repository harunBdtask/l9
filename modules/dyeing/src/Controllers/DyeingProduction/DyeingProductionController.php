<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingProduction;

use PDF;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Symfony\Component\HttpFoundation\Response;
use  SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Dyeing\Actions\DyeingProductionAction;
use SkylarkSoft\GoRMG\Dyeing\Requests\DyeingProductionFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingProduction\DyeingProduction;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\DyeingProductionFormatter;

class DyeingProductionController extends Controller
{

    public function index(Request $request)
    {
        $dyeingProductions = DyeingProduction::query()
            ->with([
                'buyer',
                'dyeingBatch',
                'textileOrder',
                'dyeingProductionDetails'
            ])
            ->withSum('dyeingProductionDetails as production_qty', 'total_cost')
            ->search($request)
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_productions.index', [
            'dyeingProductions' => $dyeingProductions,
            'factories' => $factories,
            'buyers' => $buyers
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_productions.form');
    }

    /**
     * @param DyeingProductionFormRequest $request
     * @param DyeingProduction $dyeingProduction
     * @param DyeingProductionAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(DyeingProductionFormRequest $request,
                          DyeingProduction            $dyeingProduction,
                          DyeingProductionAction      $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingProduction->fill($request->all())->save();

            $action->storeDetails(
                $dyeingProduction,
                $request->input('dyeing_production_details')
            );
            DB::commit();

            return response()->json([
                'message' => 'Dyeing production data stored successfully',
                'data' => $dyeingProduction,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingProduction $dyeingProduction
     * @param DyeingProductionFormatter $formatter
     * @return JsonResponse
     */
    public function edit(DyeingProduction $dyeingProduction, DyeingProductionFormatter $formatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch dyeing recipe successfully',
                'data' => $formatter->format($dyeingProduction),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingProductionFormRequest $request
     * @param DyeingProduction $dyeingProduction
     * @param DyeingProductionAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(DyeingProductionFormRequest $request,
                           DyeingProduction            $dyeingProduction,
                           DyeingProductionAction      $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingProduction->fill($request->all())->save();

            $action->updateDetails(
                $dyeingProduction,
                $request->input('dyeing_production_details')
            );
            DB::commit();

            return response()->json([
                'message' => 'Dyeing production data stored successfully',
                'data' => $dyeingProduction,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingProduction $dyeingProduction
     * @return RedirectResponse
     */
    public function destroy(DyeingProduction $dyeingProduction): RedirectResponse
    {
        try {
            $dyeingProduction->delete();

            Session::flash('success', 'Dyeing Production deleted successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function view($id)
    {
        $dyeingProduction = DyeingProduction::query()
            ->with([
                'buyer',
                'dyeingBatch.machineAllocations.machine',
                'textileOrder',
                'shift',
                'dyeingUnit',
                'dyeingProductionDetails.color'
            ])
            ->where('id',$id)
            ->first();
        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_productions.view',[
            'dyeingProduction' => $dyeingProduction
        ]);
    }

    public function pdf($id)
    {
        $dyeingProduction = DyeingProduction::query()
            ->with([
                'buyer',
                'dyeingBatch.machineAllocations.machine',
                'textileOrder',
                'shift',
                'dyeingUnit',
                'dyeingProductionDetails.color'
            ])
            ->where('id',$id)
            ->first();

        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('dyeing::textile_modules.dyeing_productions.pdf', [
            'dyeingProduction' => $dyeingProduction,
        ])->setPaper('a4')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);
        
        return $pdf->stream("{$id}_Dyeing_production.pdf");
    }

}
