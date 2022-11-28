<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceive;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\TrimsStore\Actions\V3\StockSummaryAction;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceive;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceiveDetail;
use SkylarkSoft\GoRMG\TrimsStore\Requests\V3\TrimsStoreReceive\TrimsStoreReceiveDetailFormRequest;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\Formatter\TrimsReceiveDetailsFormatter;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrimsStoreReceiveDetailsController extends Controller
{
    /**
     * @param TrimsStoreReceive $receive
     * @param TrimsReceiveDetailsFormatter $formatter
     * @return JsonResponse
     */
    public function getDetails(
        TrimsStoreReceive            $receive,
        TrimsReceiveDetailsFormatter $formatter
    ): JsonResponse {
        try {
            $receive->load(
                'details.buyer:id,name',
                'details.order:id,style_name',
                'details.size:id,name',
                'details.color:id,name',
                'details.uom:id,unit_of_measurement',
                'details.itemGroup:id,item_group',
                'details.supplier:id,name',
            );

            $details = $receive->getRelation('details')
                ->map(function ($detail) use ($formatter) {
                    return $formatter->format($detail);
                })->toArray();

            return response()->json([
                'message' => 'Fetch trims store receive details successfully',
                'data' => $details,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TrimsStoreReceiveDetailFormRequest $request
     * @param TrimsStoreReceive $receive
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        TrimsStoreReceiveDetailFormRequest $request,
        TrimsStoreReceive                  $receive,
        StockSummaryAction                 $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail = $receive->details()->create($request->all());
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store receive detail stored successfully',
                'data' => $detail,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param TrimsStoreReceive $receive
     * @return JsonResponse
     */
    public function storeBookingBreakdown(
        Request           $request,
        TrimsStoreReceive $receive
    ): JsonResponse {
        try {
            $bookingDetails = TrimsBookingDetails::query()
                ->with('order:id,style_name')
                ->where('booking_id', $request->get('booking_id'))
                ->where('style_name', $request->get('style_name'))
                ->get();

            $receiveDetails = $this->formatDetails($bookingDetails, $request);
            $receive->details()->createMany($receiveDetails);

            return response()->json([
                'message' => 'Trims store receive detail stored successfully',
                'data' => $receiveDetails,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function formatDetails($bookingDetails, $request): array
    {
        $receiveDetails = [];

        foreach ($bookingDetails as $detail) {
            foreach ($detail->breakdown as $breakdown) {
                $receiveDetails[] = [
                    'trims_store_receive_id' => $request->get('trims_store_receive_id'),
                    'receive_basis_id' => $request->get('receive_basis_id'),
                    'transaction_date' => $request->get('transaction_date'),
                    'buyer_id' => $request->get('buyer_id'),
                    'factory_id' => $request->get('factory_id'),
                    'style_id' => $detail['order']['id'] ?? '',
                    'po_numbers' => $detail['po_no'] ?? '',
                    'booking_id' => $detail['booking_id'] ?? '',
                    'booking_no' => $detail['booking']['unique_id'] ?? '',
                    'garments_item_id' => $breakdown['item_id'] ?? '',
                    'garments_item_name' => $breakdown['item'] ?? '',
                    'item_id' => $detail['item_id'] ?? '',
                    'sensitivity_id' => $detail['sensitivity'] ?? '',
                    'supplier_id' => $detail['booking']['supplier_id'] ?? '',
                    'item_description' => $detail['item_description'] ?? '',
                    'color_id' => $breakdown['color_id'] ?? '',
                    'size_id' => $breakdown['size_id'] ?? '',
                    'uom_id' => $detail['cons_uom_id'] ?? '',
                ];
            }
        }

        return $receiveDetails;
    }

    /**
     * @param TrimsStoreReceiveDetailFormRequest $request
     * @param TrimsStoreReceiveDetail $detail
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        TrimsStoreReceiveDetailFormRequest $request,
        TrimsStoreReceiveDetail            $detail,
        StockSummaryAction                 $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->fill($request->all())->save();
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store receive detail stored successfully',
                'data' => $detail,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TrimsStoreReceiveDetail $detail
     * @param StockSummaryAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(
        TrimsStoreReceiveDetail $detail,
        StockSummaryAction      $action
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $detail->delete();
            $action->attachToStockSummary($detail);
            $action->attachToDailyStockSummary($detail);
            DB::commit();

            return response()->json([
                'message' => 'Trims store receive detail deleted successfully',
                'data' => $detail,
                'status' => Response::HTTP_NO_CONTENT,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
