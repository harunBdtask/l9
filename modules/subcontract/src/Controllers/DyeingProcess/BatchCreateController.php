<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingBatchFormRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\BatchNotifyService;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters\SubDyeingBatchFormatter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BatchCreateController extends Controller
{
    public function index(Request $request)
    {
        $dyeingBatches = SubDyeingBatch::query()
            ->with(['factory', 'supplier'])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $parties = Buyer::query()
            ->where('party_type', 'Subcontract')
            ->factoryFilter()
            ->pluck('name', 'id')
            ->prepend('Select', 0);


        $dia_types = DiaTypesService::diaTypes();

        return view('subcontract::textile_module.dyeing_process.index', [
            'dyeingBatches' => $dyeingBatches,
            'factories' => $factories,
            'parties' => $parties,
            'diaTypes' => $dia_types,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.dyeing_process.form');
    }

    /**
     * @param SubDyeingBatchFormRequest $request
     * @param SubDyeingBatch $dyeingBatch
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(SubDyeingBatchFormRequest $request, SubDyeingBatch $dyeingBatch): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->fill($request->all())->save();
            DB::commit();

            $this->notify($dyeingBatch, 'created');

            return response()->json([
                'data' => $dyeingBatch,
                'message' => 'dyeing batch created successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingBatch $dyeingBatch
     * @param SubDyeingBatchFormatter $dyeingBatchFormatter
     * @return JsonResponse
     */
    public function edit(SubDyeingBatch $dyeingBatch, SubDyeingBatchFormatter $dyeingBatchFormatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'dyeing-batch fetched successfully',
                'data' => $dyeingBatchFormatter->format($dyeingBatch),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingBatchFormRequest $request
     * @param SubDyeingBatch $dyeingBatch
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(SubDyeingBatchFormRequest $request, SubDyeingBatch $dyeingBatch): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->fill($request->all())->save();
            DB::commit();

            $this->notify($dyeingBatch, 'updated');

            return response()->json([
                'data' => $dyeingBatch,
                'message' => 'dyeing-batch updated successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingBatch $dyeingBatch
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(SubDyeingBatch $dyeingBatch): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $dyeingBatch->batchDetails()->delete();
            $dyeingBatch->delete();
            DB::commit();

            $this->notify($dyeingBatch, 'deleted');

            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function view($id)
    {
        $dyeingBatch = SubDyeingBatch::query()
            ->with([
                'supplier',
                'color',
                'fabricComposition',
                'batchDetails.subTextileOrder',
            ])
            ->where('id', $id)
            ->first();
        $machines = collect($dyeingBatch->machineAllocations)
            ->pluck('machine.name')
            ->implode(',');

        return view('subcontract::textile_module.dyeing_process.view2', [
            'dyeingBatch' => $dyeingBatch,
            'machines' => $machines,
        ]);
    }

    public function pdf($id)
    {
        $dyeingBatch = SubDyeingBatch::query()
            ->with([
                'supplier',
                'color',
                'fabricComposition',
                'batchDetails.subDyeingBatch',
            ])
            ->where('id', $id)
            ->first();
        $machines = collect($dyeingBatch->machineAllocations)
            ->pluck('machine.name')
            ->implode(',');

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.dyeing_process.pdf', [
                'dyeingBatch' => $dyeingBatch,
                'machines' => $machines,
            ])
            ->setPaper('a4')
            ->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("batch-view.pdf");
    }

    private function notify($data, $type)
    {
        (new BatchNotifyService())
            ->setData($data)
            ->setType($type)
            ->notify();
    }
}
