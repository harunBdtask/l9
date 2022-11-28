<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Compactor;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\Actions\CompactorDetailsAction;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Compactor\Compactor;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use SkylarkSoft\GoRMG\Dyeing\Requests\Compactor\CompactorFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\CompactorFormatter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CompactorController extends Controller
{

    public function index(Request $request)
    {
        $compactors = Compactor::query()
            ->search($request)
            ->withSum('compactorDetails', 'finish_qty')
            ->orderBy('id', 'desc')
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'textile_modules.compactors.index', [
            'compactors' => $compactors,
            'factories' => $factories,
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.compactors.form');
    }

    /**
     * @param CompactorFormRequest $request
     * @param Compactor $compactor
     * @param CompactorDetailsAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CompactorFormRequest   $request,
                          Compactor              $compactor,
                          CompactorDetailsAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $compactor->fill($request->all())->save();
            $action->storeDetails($compactor, $request->input('compactor_details'));
            DB::commit();

            return response()->json([
                'message' => 'Compactor stored successfully',
                'data' => $compactor,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Compactor $compactor
     * @param CompactorFormatter $formatter
     * @return JsonResponse
     */
    public function edit(Compactor $compactor, CompactorFormatter $formatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch compactor successfully',
                'data' => $formatter->format($compactor),
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param CompactorFormRequest $request
     * @param Compactor $compactor
     * @param CompactorDetailsAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(CompactorFormRequest   $request,
                           Compactor              $compactor,
                           CompactorDetailsAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $compactor->fill($request->all())->save();
            $action->updateDetails($compactor, $request->input('compactor_details'));
            DB::commit();

            return response()->json([
                'message' => 'Compactor updated successfully',
                'data' => $compactor,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Compactor $compactor
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Compactor $compactor): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $compactor->compactorDetails()->delete();
            $compactor->delete();
            DB::commit();

            Session::flash('success', 'Compactor deleted successfully');
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }

}
