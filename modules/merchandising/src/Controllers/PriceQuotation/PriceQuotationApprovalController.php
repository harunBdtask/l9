<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\PriceQuotationApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Actions\PriceQuotationNotification;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use Symfony\Component\HttpFoundation\Response;

class PriceQuotationApprovalController extends Controller
{
    /**
     * @return JsonResponse
     */

    public function getUnapprovedData(Request $request)
    {
        $unApprovedData = PriceQuotationApprovalService::for(Approval::PRICE_QUOTATION)
            ->setRequest($request)
            ->setBuyer($request->get('buyer'))
            ->getUnapprovedData();

        return response()->json($unApprovedData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateApprovedStatus(Request $request): JsonResponse
    {
        try {
            PriceQuotation::query()->whereIn('id', $request)->update([
                'is_approve' => null,
                'step' => 0
            ]);
            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveUnapprovedRequest(Request $request): JsonResponse
    {
        try {
            $price_quotation = PriceQuotation::query()->findOrFail($request->params['quotation_id']);
            $price_quotation->unapproved_request = $request->params['unapproved_request'];
            $price_quotation->save();
            PriceQuotationNotification::send($price_quotation);
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data successfully stored",
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws \Throwable
     */
    public function cancelAndRework(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $type = $request->get('type');
            $query = PriceQuotation::query()->whereIn('quotation_id', $request->get('quotation_id'));
            if ($type == 'cancel') {
                $query->update(['cancel_status' => 1]);
            }
            if ($type == 'rework') {
                $query->each(function ($priceQuotation) {
                    $priceQuotation->update(['rework_status' => !$priceQuotation->rework_status]);
                });
            }
            DB::commit();

            return response()->json('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
