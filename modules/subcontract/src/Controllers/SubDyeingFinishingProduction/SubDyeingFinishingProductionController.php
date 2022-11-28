<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubDyeingFinishingProduction;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingFinishingProduction\SubDyeingFinishingProduction;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\FinishingProductionFormRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters\SubDyeingFinishingProductionFormatter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDyeingFinishingProductionController extends Controller
{
    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $entryBasis = SubDyeingFinishingProduction::ENTRY_BASIS;

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $shifts = Shift::query()
            ->pluck('shift_name', 'id')
            ->prepend('Select', 0);

        $machines = DyeingMachine::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $colors = Color::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $parties = Buyer::query()
            ->where('party_type', 'Subcontract')
            ->factoryFilter()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $dyeingFinishingProductions = SubDyeingFinishingProduction::query()
            ->with([
                'subDyeingBatch.machineAllocations',
                'finishingProductionDetails.color',
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        return view('subcontract::textile_module.finishing-production.index', [
            'dyeingFinishingProductions' => $dyeingFinishingProductions,
            'entryBasis' => $entryBasis,
            'factories' => $factories,
            'machines' => $machines,
            'colors' => $colors,
            'shifts' => $shifts,
            'parties' => $parties,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        return view('subcontract::textile_module.finishing-production.form');
    }

    /**
     * @param FinishingProductionFormRequest $request
     * @param SubDyeingFinishingProduction $dyeingFinishingProduction
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        FinishingProductionFormRequest $request,
        SubDyeingFinishingProduction   $dyeingFinishingProduction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $dyeingFinishingProduction->fill($request->all())->save();
            $dyeingFinishingProduction->finishingProductionDetails()
                ->createMany($request->input('finishing_production_details'));
            DB::commit();

            return response()->json([
                'message' => 'Finishing production store successfully',
                'data' => $dyeingFinishingProduction,
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
     * @param SubDyeingFinishingProduction $dyeingFinishingProduction
     * @param SubDyeingFinishingProductionFormatter $dyeingFinishingProductionFormatter
     * @return JsonResponse
     */
    public function edit(
        SubDyeingFinishingProduction          $dyeingFinishingProduction,
        SubDyeingFinishingProductionFormatter $dyeingFinishingProductionFormatter
    ): JsonResponse {
        try {
            return response()->json([
                'message' => 'Fetch finishing production successfully',
                'data' => $dyeingFinishingProductionFormatter->format($dyeingFinishingProduction),
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
     * @param FinishingProductionFormRequest $request
     * @param SubDyeingFinishingProduction $dyeingFinishingProduction
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        FinishingProductionFormRequest $request,
        SubDyeingFinishingProduction   $dyeingFinishingProduction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $dyeingFinishingProduction->fill($request->all())->save();
            // Update details
            foreach ($request->input('finishing_production_details') as $detail) {
                $dyeingFinishingProduction->finishingProductionDetails()->updateOrCreate([
                    'id' => $detail['id'],
                ], $detail);
            }
            DB::commit();

            return response()->json([
                'message' => 'Finishing production store successfully',
                'data' => $dyeingFinishingProduction,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => $dyeingFinishingProduction,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingFinishingProduction $dyeingFinishingProduction
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingFinishingProduction $dyeingFinishingProduction): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $dyeingFinishingProduction->finishingProductionDetails()->delete();
            $dyeingFinishingProduction->delete();
            DB::commit();

            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
