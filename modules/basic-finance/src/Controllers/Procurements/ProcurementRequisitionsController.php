<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\Procurements;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\Models\Procurements\ProcurementRequisition;
use SkylarkSoft\GoRMG\BasicFinance\Requests\Procurements\ProcurementRequisitionFormRequest;
use SkylarkSoft\GoRMG\BasicFinance\Services\Formatters\ProcurementRequisitionFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProcurementRequisitionsController extends Controller
{

    public function index(Request $request)
    {
        $procurementRequisitions = ProcurementRequisition::query()
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        return view('basic-finance::procurements.requisitions.index', [
            'procurementRequisitions' => $procurementRequisitions,
        ]);
    }

    public function create()
    {
        return view('basic-finance::procurements.requisitions.form');
    }

    /**
     * @param ProcurementRequisitionFormRequest $request
     * @param ProcurementRequisition $procurementRequisition
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ProcurementRequisitionFormRequest $request,
                          ProcurementRequisition            $procurementRequisition): JsonResponse
    {
        try {
            DB::beginTransaction();
            $procurementRequisition->fill($request->all())->save();
            $procurementRequisition->procurementRequisitionDetails()
                ->createMany($request->input('procurement_requisition_details'));
            DB::commit();

            return response()->json([
                'message' => 'Procurement requisition stored successfully',
                'data' => $procurementRequisition,
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

    public function show(ProcurementRequisition $procurementRequisition)
    {
        return view('basic-finance::procurements.requisitions.view', [
            'procurementRequisition' => $procurementRequisition->load('procurementRequisitionDetails'),
        ]);
    }

    /**
     * @param ProcurementRequisition $procurementRequisition
     * @param ProcurementRequisitionFormatter $requisitionFormatter
     * @return JsonResponse
     */
    public function edit(ProcurementRequisition          $procurementRequisition,
                         ProcurementRequisitionFormatter $requisitionFormatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch edited data successfully',
                'data' => $requisitionFormatter->format($procurementRequisition),
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
     * @param ProcurementRequisitionFormRequest $request
     * @param ProcurementRequisition $procurementRequisition
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(ProcurementRequisitionFormRequest $request,
                           ProcurementRequisition            $procurementRequisition): JsonResponse
    {
        try {
            DB::beginTransaction();
            $procurementRequisition->fill($request->all())->save();

            foreach ($request->input('procurement_requisition_details') as $detail) {
                $procurementRequisition->procurementRequisitionDetails()->updateOrCreate([
                    'id' => $detail['id'],
                ], $detail);
            }
            DB::commit();

            return response()->json([
                'message' => 'Procurement requisition updated successfully',
                'data' => $procurementRequisition,
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
     * @param ProcurementRequisition $procurementRequisition
     * @return RedirectResponse
     */
    public function destroy(ProcurementRequisition $procurementRequisition): RedirectResponse
    {
        try {
            $procurementRequisition->delete();
            Session::flash('success', 'Procurement requisition deleted successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
