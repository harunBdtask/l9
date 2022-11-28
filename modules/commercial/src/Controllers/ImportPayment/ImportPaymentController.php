<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\ImportPayment;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportDocumentAcceptance;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportPayment;
use SkylarkSoft\GoRMG\Commercial\Services\Commercial\AdjustSourceService;
use SkylarkSoft\GoRMG\Commercial\Services\Commercial\ImportPaymentFormat;
use SkylarkSoft\GoRMG\Commercial\Services\Commercial\PaymentHeadService;
use Symfony\Component\HttpFoundation\Response;

class ImportPaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $importPayments = ImportPayment::query()->filter($search)->orderBy('id', 'desc')->paginate();

        return view('commercial::import-payment.import-payment-list', compact('importPayments', 'search'));
    }

    public function create()
    {
        return view('commercial::import-payment.import-payment-form');
    }

    public function searchImportDocuments(Request $request): JsonResponse
    {
        $request->validate([
            'factory_id' => 'required',
            'item_id' => 'required',
            'supplier_id' => 'required',
        ]);

        $factoryId = $request->get('factory_id');
        $itemId = $request->get('item_id');
        $supplierId = $request->get('supplier_id');
        $lcNo = $request->get('lc_no');

        $target = ImportDocumentAcceptance::query()
            ->with([
                'shippingInfo',
                'piInfos',
                'bToBMarginLC',
            ])
            ->where('factory_id', $factoryId)
            ->where('supplier_id', $supplierId)
            ->whereHas('piInfos', function ($q) use ($itemId) {
                $q->where('item_id', $itemId);
            })
            ->when($lcNo, function ($q) use ($lcNo) {
                $q->whereHas('bToBMarginLC', function ($q) use ($lcNo) {
                    $q->where('lc_number', $lcNo);
                });
            })
            ->get()->map(function ($data) use ($itemId, $factoryId, $supplierId) {
                return ImportPaymentFormat::searchFormat($data, $itemId, $factoryId, $supplierId);
            });

        return response()->json($target);
    }

    public function loadCommonData(): JsonResponse
    {
        $data['adjustSources'] = AdjustSourceService::data();
        $data['paymentHeads'] = PaymentHeadService::data();

        return response()->json($data, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $importPayment = ImportPayment::query()->find($request->get('id'))->update($request->all());
            } else {
                $importPayment = ImportPayment::query()->create($request->all());
            }

            return response()->json([
                'importPayment' => $importPayment,
                'message' => ApplicationConstant::S_CREATED,
            ], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => ApplicationConstant::SOMETHING_WENT_WRONG,
                'msg' => $exception->getMessage(),
            ], Response::HTTP_CREATED);
        }
    }

    public function get($id): JsonResponse
    {
        try {
            $rawData = ImportPayment::query()
                ->with([
                    'importDocumentAcceptance',
                    'importDocumentAcceptance.shippingInfo',
                    'importDocumentAcceptance.piInfos',
                    'importDocumentAcceptance.bToBMarginLC',
                ])
                ->findOrFail($id);
            $data = ImportPaymentFormat::editFormat($rawData);

            return response()->json([
                'importPayment' => $data,
                'message' => 'Data',
            ], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => ApplicationConstant::SOMETHING_WENT_WRONG,
                'msg' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(ImportPayment $importPayment): \Illuminate\Http\RedirectResponse
    {
        try {
            $importPayment->delete();
            Session::flash('success', 'Data Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', "{$exception->getMessage()}");
        }

        return redirect()->back();
    }
}
