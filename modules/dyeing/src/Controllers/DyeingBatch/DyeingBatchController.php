<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\DyeingBatch;

use Exception;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Requests\DyeingBatch\DyeingBatchRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\DyeingBatchFormatter;

class DyeingBatchController extends Controller
{

    public function index(Request $request)
    {
        $batches = DyeingBatch::query()
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', '0');

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', '0');

        $colors = Color::query()
            ->pluck('name', 'id')
            ->prepend('Select', '0');

        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_batches.index', [
            'batches' => $batches,
            'factories' => $factories,
            'buyers' => $buyers,
            'colors' => $colors,
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_batches.form');
    }

    /**
     * @param DyeingBatchRequest $request
     * @param DyeingBatch $dyeingBatch
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(DyeingBatchRequest $request, DyeingBatch $dyeingBatch): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Batch stored successfully',
                'data' => $dyeingBatch,
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
     * @param DyeingBatch $dyeingBatch
     * @param DyeingBatchFormatter $formatter
     * @return JsonResponse
     */
    public function edit(DyeingBatch $dyeingBatch, DyeingBatchFormatter $formatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch batch data successfully',
                'data' => $formatter->format($dyeingBatch),
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
     * @param DyeingBatchRequest $request
     * @param DyeingBatch $dyeingBatch
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(DyeingBatchRequest $request, DyeingBatch $dyeingBatch): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Batch updated successfully',
                'data' => $dyeingBatch,
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
     * @param DyeingBatch $dyeingBatch
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(DyeingBatch $dyeingBatch): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->delete();
            DB::commit();

            Session::flash('success', 'Batch deleted successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function view(DyeingBatch $dyeingBatch)
    {
        $dyeingBatch->load('dyeingBatchDetails', 'machineAllocations');

        $machines = collect($dyeingBatch->machineAllocations)
            ->pluck('machine.name')
            ->implode(',');

        return view(PackageConst::VIEW_PATH . 'textile_modules.dyeing_batches.view', [
            'dyeingBatch' => $dyeingBatch,
            'machines' => $machines,
        ]);
    }

}
