<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Tumble;

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
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingTumble\SubDyeingTumble;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\SubDyeingTumbleFormRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters\SubDyeingTumbleFormatter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubDyeingTumbleController extends Controller
{
    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $entryBasis = SubDyeingTumble::ENTRY_BASIS;
        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select', 0);
        $shifts = Shift::query()->pluck('shift_name', 'id')->prepend('Select', 0);
        $machines = DyeingMachine::query()->pluck('name', 'id')->prepend('Select', 0);
        $colors = Color::query()->pluck('name', 'id')->prepend('Select', 0);
        $subDyeingTumbles = SubDyeingTumble::query()->with('tumbleDetails.color')
            ->search($request)
            ->orderBy('id', 'desc')
            ->paginate();

        return view('subcontract::textile_module.tumble.index', [
            'subDyeingTumbles' => $subDyeingTumbles,
            'entryBasis' => $entryBasis,
            'factories' => $factories,
            'shifts' => $shifts,
            'machines' => $machines,
            'colors' => $colors,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        return view('subcontract::textile_module.tumble.form');
    }

    /**
     * @param SubDyeingTumbleFormRequest $request
     * @param SubDyeingTumble $dyeingTumble
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubDyeingTumbleFormRequest $request, SubDyeingTumble $dyeingTumble): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingTumble->fill($request->all())->save();
            $dyeingTumble->tumbleDetails()
                ->createMany($request->input('tumble_details'));
            DB::commit();

            return response()->json([
                'message' => 'Finishing production store successfully',
                'data' => $dyeingTumble,
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
     * @param SubDyeingTumble $dyeingTumble
     * @param SubDyeingTumbleFormatter $subDyeingTumbleFormatter
     * @return JsonResponse
     */
    public function edit(
        SubDyeingTumble          $dyeingTumble,
        SubDyeingTumbleFormatter $subDyeingTumbleFormatter
    ): JsonResponse {
        try {
            return response()->json([
                'message' => 'Fetch tumble data successfully',
                'data' => $subDyeingTumbleFormatter->format($dyeingTumble),
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
     * @param SubDyeingTumbleFormRequest $request
     * @param SubDyeingTumble $dyeingTumble
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubDyeingTumbleFormRequest $request, SubDyeingTumble $dyeingTumble): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingTumble->fill($request->all())->save();
            // Update details
            foreach ($request->input('tumble_details') as $detail) {
                $dyeingTumble->tumbleDetails()->updateOrCreate([
                    'id' => $detail['id'],
                ], $detail);
            }
            DB::commit();

            return response()->json([
                'message' => 'Finishing production store successfully',
                'data' => $dyeingTumble,
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
     * @param SubDyeingTumble $dyeingTumble
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingTumble $dyeingTumble): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $dyeingTumble->tumbleDetails()->delete();
            $dyeingTumble->delete();
            DB::commit();

            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
