<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Throwable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Requests\FabricBarcodeDetailRequest;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricBarcodeDetail;

class FabricBarcodeDetailController extends Controller
{
    /**
     * @throws Exception
     */
    public function store(FabricBarcodeDetailRequest $request): JsonResponse
    {
        $detail = FabricReceiveDetail::query()
            ->withSum('receiveReturnDetails', 'return_qty')
            ->findOrFail($request->get('detail')['id']);

        $receiveDetail = $request->get('detail');
        $barcodeQty = $request->get('qty');
        $totalQty = collect($barcodeQty)->sum();
        $balanceQty = $detail->receive_qty - $detail->receive_return_details_sum_return_qty;

        if (number_format($balanceQty, 4) != number_format($totalQty, 4)) {
            throw new Exception('Receive qty not match!');
        }

        try {
            DB::beginTransaction();
            foreach ($barcodeQty as $qty) {
                $receiveDetail['fabric_receive_id'] = $receiveDetail['receive_id'];
                $receiveDetail['fabric_receive_detail_id'] = $receiveDetail['id'];
                $receiveDetail['qty'] = $qty;
                $receiveDetail['amount'] = number_format($qty * $receiveDetail['rate'], 4);

                $barcodeDetail = new FabricBarcodeDetail();
                $barcodeDetail->fill($receiveDetail)->save();
            }
            DB::commit();

            return response()->json('Barcode generate successfully!', Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param FabricReceiveDetail $detail
     * @return RedirectResponse
     */
    public function destroy(FabricReceiveDetail $detail): RedirectResponse
    {
        $barcodes = collect($detail->barcodeDetails)
            ->where('status', FabricBarcodeDetail::USED)
            ->count();

        if ($barcodes) {
            Session::flash('error', "Barcode already scan, You can't delete this barcodes!");

            return back();
        }

        $detail->barcodeDetails()->delete();
        Session::flash('success', "Barcode delete successfully!");

        return back();
    }
}
