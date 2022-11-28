<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\YarnTransfer;
use SkylarkSoft\GoRMG\Inventory\Models\YarnTransferDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\YarnTransferDetailRequest;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class YarnTransferDetailController extends Controller
{
    public function list(YarnTransfer $yarnTransfer): JsonResponse
    {
        $this->response['transfer'] = $yarnTransfer;
        $this->response['details'] = $yarnTransfer->details()->get();

        return response()->json($this->response);
    }

    public function show($id): JsonResponse
    {
        $detail = YarnTransferDetail::with([
            'composition', 'yarn_count', 'type', 'floor', 'room', 'rack', 'shelf', 'bin', 'store', 'uom', 'yarnTransfer.fromStore', 'yarnTransfer.toStore'
        ])
        ->where('yarn_transfer_id', $id)
        ->orderByDesc('id')
        ->get()->map(function ($yarn) {
                $summary = (new YarnStockSummaryService())->summary($yarn);
                return array_merge(
                    $yarn->toArray(), [
                        'current_stock' => $summary->balance,
                        'rate' => $summary->receive_amount / $summary->receive_qty,
                        'receive_qty' => $summary->receive_qty,
                    ]
                );
            });

        return response()->json($detail);
    }

    /**
     * @throws Throwable
     */
    public function store(YarnTransferDetailRequest $request): JsonResponse
    {
        try {
            \DB::beginTransaction();
            $id = $request->input('id');
            $transfer = YarnTransferDetail::query()->firstOrNew(['id' => $id]);
            $request->merge(['transfer_value' => $request->input('transfer_qty') * $request->input('rate')]);
            $transfer->fill($request->all());
            $transfer->save();
            \DB::commit();

            $this->response['message'] = $id ? S_UPDATE_MSG : S_SAVE_MSG;
            $this->statusCode = $id ? Response::HTTP_OK : Response::HTTP_CREATED;

        } catch (Throwable $e) {
            \DB::rollBack();
            $this->response['message'] = $e->getMessage();
            $this->response['line'] = $e->getLine();
            $this->response['file'] = $e->getFile();
            $this->statusCode = Response::HTTP_NOT_ACCEPTABLE;
        }

        return response()->json($this->response, $this->statusCode);
    }


    /**
     * @throws Throwable
     */
    public function delete(YarnTransferDetail $detail): JsonResponse
    {
        try {
            DB::beginTransaction();
            // TODO: Check validation in summary table
            $detail->delete();
            $this->response['message'] = S_DEL_MSG;
            DB::commit();
        } catch (\Exception $e) {
            $this->response['message'] = E_DEL_MSG;
            $this->statusCode = Response::HTTP_FORBIDDEN;
            DB::rollBack();
        }

        return response()->json($this->response, $this->statusCode);
    }
}
