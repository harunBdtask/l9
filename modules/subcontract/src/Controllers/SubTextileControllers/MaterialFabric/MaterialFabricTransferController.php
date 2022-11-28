<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Actions\TransferStockSummaryAction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreFabricTransfer;
use SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests\FabricTransferFormRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MaterialFabricTransferController extends Controller
{
    public function index()
    {
        $fabricTransfer = SubGreyStoreFabricTransfer::query()
            ->with([
                'details.detailMSI',
                'details.toOrderDetail.subTextileOperation',
                'details.toOrderDetail.bodyPart',
            ])
            ->orderBy('id', 'desc')->paginate();

        return view('subcontract::textile_module.fabric-transfer.index', [
            'fabricTransfer' => $fabricTransfer,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.fabric-transfer.form');
    }

    /**
     * @param FabricTransferFormRequest $request
     * @param SubGreyStoreFabricTransfer $subGreyStoreFabricTransfer
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        FabricTransferFormRequest  $request,
        SubGreyStoreFabricTransfer $subGreyStoreFabricTransfer
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $subGreyStoreFabricTransfer->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Fabric Transfer created successfully',
                'data' => $subGreyStoreFabricTransfer,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubGreyStoreFabricTransfer $transfer
     * @return JsonResponse
     */
    public function edit(SubGreyStoreFabricTransfer $transfer): JsonResponse
    {
        try {
            return response()->json([
                'data' => $transfer,
                'message' => 'Data Fetch successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(
        FabricTransferFormRequest  $request,
        SubGreyStoreFabricTransfer $transfer
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $transfer->fill($request->all())->save();
            DB::commit();

            return response()->json([
                'message' => 'Update successfully',
                'data' => $transfer,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubGreyStoreFabricTransfer $transfer
     * @param TransferStockSummaryAction $action
     * @return RedirectResponse
     * @throws Throwable
     */
    public function delete(
        SubGreyStoreFabricTransfer $transfer,
        TransferStockSummaryAction $action
    ): RedirectResponse {
        try {
            DB::beginTransaction();

            $transfer->details()->each(function ($detail) use ($action) {
                $detail->load('detailMSI');
                $detail->detailMSI()->delete();
                $detail->delete();
                $action->attachToStockSummaryReport($detail);
                $action->attachToDailyStockSummaryReport($detail);
            });

            $transfer->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            DB::rollBack();
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function view($id)
    {
        $fabricTransfer = SubGreyStoreFabricTransfer::query()
            ->with([
                'details.transfer',
                'details.toOrderDetail.subTextileOperation',
                'details.toOrderDetail.bodyPart',
                'details.detailMSI',
                'fromCompany',
                'toCompany',
            ])
            ->findOrFail($id);

        return view('subcontract::textile_module.fabric-transfer.view', [
            'fabricTransfer' => $fabricTransfer,
        ]);
    }

    public function pdf($id)
    {
        $fabricTransfer = SubGreyStoreFabricTransfer::query()
            ->with([
                'details.transfer',
                'details.toOrderDetail.subTextileOperation',
                'details.toOrderDetail.bodyPart',
                'details.detailMSI',
                'fromCompany',
                'toCompany',
            ])
            ->findOrFail($id);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.fabric-transfer.pdf', [
                'fabricTransfer' => $fabricTransfer,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("material-fabric-transfer.pdf");
    }
}
