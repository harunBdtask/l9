<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\ServiceBookingApprovalService;
use SkylarkSoft\GoRMG\Approval\Services\TrimsBookingApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ServiceBookingApprovalController extends Controller
{
    const PAGE_NAME = 'Service Approval';

    public function index()
    {
        return view('approval::approvals.modules.service-booking');
    }

    public function create(Request $request)
    {
        try {

            $bookings = ServiceBookingApprovalService::for(Approval::SERVICE_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->response();

            $response = [
                'data' => $bookings,
                'status' => Response::HTTP_OK,
                'message' => 'Trims booking fetched successfully',
            ];

            $response = [
                'data' => $bookings,
                'status' => Response::HTTP_OK,
                'message' => 'Fabric booking fetched successfully',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $service = ServiceBookingApprovalService::for(Approval::SERVICE_APPROVAL)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->store();

            $response = [
                'data' => $service,
                'status' => Response::HTTP_OK,
                'message' => 'Updated Successfully!',
            ];
            DB::commit();

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function unApprovedRequests(Request $request): JsonResponse
    {
        $bookings = ServiceBookingApprovalService::for(Approval::SERVICE_APPROVAL)
            ->setRequest($request)
            ->setBuyer($request->get('buyer'))
            ->getUnapprovedData();


        return response()->json($bookings);
    }

    public function unApprove(Request $request): JsonResponse
    {
        try {
            $quotations = FabricServiceBooking::query()->whereIn('id', $request->submitId)->update([
                'is_approved' => 0,
                'step' => 0
            ]);

            return response()->json(['message' => 'Successfully Updated!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!'], Response::HTTP_BAD_REQUEST);
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
            $query = FabricServiceBooking::query()->whereIn('id', $request->get('bookings_id'));
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
