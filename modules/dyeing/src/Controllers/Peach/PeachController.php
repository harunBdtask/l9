<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Peach;

use Exception;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\PeachFormatter;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Actions\PeachDetailAction;
use SkylarkSoft\GoRMG\Dyeing\Requests\Peach\PeachFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Peach\Peach;

class PeachController extends Controller
{

    public function index(Request $request)
    {
        $peaches = Peach::query()
            ->orderBy('id', 'desc')
            ->paginate();

        return view(PackageConst::VIEW_PATH . 'textile_modules.peach.index', [
            'peaches' => $peaches,
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.peach.form');
    }

    /**
     * @param PeachFormRequest $request
     * @param Peach $peach
     * @param PeachDetailAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(PeachFormRequest $request, Peach $peach, PeachDetailAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $peach->fill($request->all())->save();
            $action->storeDetails($peach, $request->input('peach_details'));
            DB::commit();

            return response()->json([
                'message' => 'Peach stored successfully',
                'data' => $peach,
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
     * @param Peach $peach
     * @param PeachFormatter $formatter
     * @return JsonResponse
     */
    public function edit(Peach $peach, PeachFormatter $formatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch peach successfully',
                'data' => $formatter->format($peach),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param PeachFormRequest $request
     * @param Peach $peach
     * @param PeachDetailAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(PeachFormRequest $request, Peach $peach, PeachDetailAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $peach->fill($request->all())->save();
            $action->updateDetails($peach, $request->input('peach_details'));
            DB::commit();

            return response()->json([
                'message' => 'Peach stored successfully',
                'data' => $peach,
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
     * @param Peach $peach
     * @return RedirectResponse
     */
    public function destroy(Peach $peach): RedirectResponse
    {
        try {
            $peach->peachDetails()->delete();
            $peach->delete();
            Session::flash('success', 'Peach deleted successfully');
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }

}
