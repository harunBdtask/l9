<?php

namespace SkylarkSoft\GoRMG\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Procurement\Models\ProcurementRequisition;
use SkylarkSoft\GoRMG\procurement\PackageConst;
use SkylarkSoft\GoRMG\Procurement\Requests\RequisitionFormRequest;
use SkylarkSoft\GoRMG\Procurement\Services\Formatters\RequisitionFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RequisitionController extends Controller
{
    public function index(Request $request)
    {
        $procurementRequisitions = ProcurementRequisition::query()
            ->with(['createdBy','department','approvalBy'])
            ->search($request)
            ->orderBy('id', 'desc')
            ->paginate();

        return view('procurement::requisitions.index', compact('procurementRequisitions'));
    }

    public function create()
    {
        return view('procurement::requisitions.form');
    }

    /**
     * @param ProcurementRequisitionFormRequest $request
     * @param ProcurementRequisition $procurementRequisition
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(RequisitionFormRequest $request, ProcurementRequisition $procurementRequisition): JsonResponse
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

    public function show(ProcurementRequisition $procurementRequisition, Request $request)
    {

        $procurementRequisition->load('procurementRequisitionDetails' , 'department');
        
        if ($request->get('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::VIEW_NAMESPACE . '::requisitions.pdf', compact('procurementRequisition'))
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

            return $pdf->stream('requisition-' . date('d:m:Y') . '.pdf');
        }

        return view('procurement::requisitions.view', compact('procurementRequisition'));
    }

    /**
     * @param ProcurementRequisition $procurementRequisition
     * @param ProcurementRequisitionFormatter $requisitionFormatter
     * @return JsonResponse
     */
    public function edit(ProcurementRequisition $procurementRequisition, RequisitionFormatter $requisitionFormatter): JsonResponse
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
    public function update(RequisitionFormRequest $request, ProcurementRequisition  $procurementRequisition): JsonResponse
    {
        try {
            DB::beginTransaction();
            $procurementRequisition->fill($request->all())->save();

            foreach ($request->input('procurement_requisition_details') as $detail) {
                $procurementRequisition->procurementRequisitionDetails()->updateOrCreate([
                    'id' => $detail['id'] ?? '',
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

    public function pdf(Request $request)
    {
        if ($request->get('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::print.procurement', [

            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

            return $pdf->stream('ledger-report-' . date('d:m:Y') . '.pdf');
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
            $procurementRequisition->procurementRequisitionDetails()->delete();
            Session::flash('success', 'Procurement requisition deleted successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
