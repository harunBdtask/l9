<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Samples;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Requests\SampleBookingRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\SampleBookingService;

abstract class SampleBookingController extends Controller
{

    protected $types = [];
    /**
     * @var SampleBookingService
     */
    public $sampleBookingService;

    public function store(SampleBookingRequest $request): JsonResponse
    {
        try {
            if ( $request->input('id') ) {
                $sampleBooking = $this->sampleBookingService->updateBooking($request);
                $this->response['message'] = 'Successfully Updated';
                $this->response['booking'] = $sampleBooking;
                return response()->json($this->response);
            }

            $sampleBooking = $this->sampleBookingService->saveBooking($request);

            $this->statusCode = 201;
            $this->response['message'] = 'Successfully Created';
            $this->response['booking'] = $sampleBooking;

            return response()->json($this->response, $this->statusCode);
        } catch (\Exception $e) {
            $this->response['message'] = E_SAVE_MSG;
            $this->response['errMsg'] = $e->getMessage();
            $this->statusCode = 400;
            return response()->json($this->response, 400);
        }
    }

    public function searchRequisitionData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = SampleRequisition::with([
            'factory:id,group_name as name',
            'buyer:id,name',
            'department:id,product_department as name',
            'merchant:id,first_name,last_name,screen_name as name'
        ])
            ->where('factory_id', $request->factory_id)
            ->where('buyer_id', $request->buyer_id)
            ->whereIn('sample_stage', $request->types);

        if ( $requisitionNo = $request->requisition_no ) {
            $query->where('requisition_id', $requisitionNo);
        }

        if ( $styleName = $request->style_name ) {
            $query->where('style_name', $styleName);
        }

        if ( $startDate && $endDate ) {
            $query->whereBetween('req_date', [$startDate, $endDate]);
        }

        if ( $fabricNatureId = $request->fabric_nature_id ) {
            $query->whereHas('fabrics', function ($q) use ($fabricNatureId) {
                return $q->where('fabric_nature_id', $fabricNatureId);
            });
        }

        if ( $fabricSourceId = $request->fabric_source ) {
            $query->where('fabric', function ($q) use ($fabricSourceId) {
                return $q->where('fabric_source_id', $fabricSourceId);
            });
        }


        return $query->get()->map(function ($requisition) use ($request) {
            return array_merge(
                $request->all(),
                [
                    'id'                 => $requisition->id,
                    'requisition_no'     => $requisition->requisition_id,
                    'year'               => Carbon::parse($requisition->req_date)->year,
                    'style_name'         => $requisition->style_name,
                    'factory_name'       => $requisition->factory->name,
                    'buyer_name'         => $requisition->buyer->name,
                    'product_department' => $requisition->department->name,
                    'merchant'           => $requisition->merchant->name,

                ]
            );
        });
    }

    public function getDetails(SampleRequisition $requisition): Collection
    {
        return $requisition
            ->fabrics()
            ->with('gmtsItem:id,name')
            ->with('bodyPart:id,name')
            ->with('bodyPart:id,name,type')
            ->with('fabricNature:id,name')
            ->with('colorType:id,color_types as name')
            ->get();
    }
}
