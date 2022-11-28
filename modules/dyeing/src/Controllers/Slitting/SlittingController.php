<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Slitting;

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
use SkylarkSoft\GoRMG\Dyeing\Requests\SlittingFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Actions\SlittingDetailAction;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Slitting\Slitting;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\SlittingFormatter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SlittingController extends Controller
{

    public function index(Request $request)
    {
        $slittings = Slitting::query()
            ->search($request)
            ->orderBy('id', 'desc')
            ->withSum('slittingDetails as total_finish', 'finish_qty')
            ->paginate();
        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);
        return view(PackageConst::VIEW_PATH . 'textile_modules.slitting.index', [
            'slittings' => $slittings,
            'factories' => $factories,
            'buyers' => $buyers,
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.slitting.form');
    }

    /**
     * @param SlittingFormRequest $request
     * @param Slitting $slitting
     * @param SlittingDetailAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SlittingFormRequest  $request,
                          Slitting             $slitting,
                          SlittingDetailAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $slitting->fill($request->all())->save();
            $action->storeDetails($slitting, $request->input('slitting_details'));
            DB::commit();

            return response()->json([
                'message' => 'Slitting stored successfully',
                'data' => $slitting,
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
     * @param Slitting $slitting
     * @param SlittingFormatter $formatter
     * @return JsonResponse
     */
    public function edit(Slitting $slitting, SlittingFormatter $formatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch slitting data successfully',
                'data' => $formatter->format($slitting),
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
     * @param SlittingFormRequest $request
     * @param Slitting $slitting
     * @param SlittingDetailAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SlittingFormRequest  $request,
                           Slitting             $slitting,
                           SlittingDetailAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $slitting->fill($request->all())->save();
            $action->updateDetails($slitting, $request->input('slitting_details'));
            DB::commit();

            return response()->json([
                'message' => 'Slitting updated successfully',
                'data' => $slitting,
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
     * @param Slitting $slitting
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Slitting $slitting): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $slitting->slittingDetails()->delete();
            $slitting->delete();
            DB::commit();

            Session::flash('success', 'Slitting deleted successfully');
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return back();
        }
    }

}
