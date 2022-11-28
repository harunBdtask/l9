<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use SkylarkSoft\GoRMG\Merchandising\Services\Filters\BookingFilters;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use Symfony\Component\HttpFoundation\Response;

class BookingSearchController extends Controller
{
    public function bookingSearch(Request $request)
    {
        try {
            $factoryId = $request->get('factory_id');
            $buyerId = $request->get('buyer_id');
            $fabricNatureId = $request->get('fabric_nature');
            $uomId = $request->get('uom_id');
            $year = $request->get('year');
            $jobNo = $request->get('job_no');
            $internalRefNo = $request->get('internal_ref_no');
            $fileNo = $request->get('file_no');
            $styleName = $request->get('style_name');
            $PONo = $request->get('order_no');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');
            $level = $request->get('level');

            $fabricActionStatus = MerchandisingVariableSettings::query()->where(['factory_id' => $factoryId, 'buyer_id' => $buyerId])->first();
            $fabricActionStatus = isset($fabricActionStatus) ? $fabricActionStatus['variables_details']['budget_approval_required_for_booking']['fabric_part'] : null;

            $fabric_costings = Budget::with(['order.purchaseOrders' => function ($q) use ($PONo) {
                $q->when($PONo, function ($query) use ($PONo) {
                    $query->where('po_no', $PONo);
                });
            }, 'fabricCosting'])
                ->when($jobNo, function ($query) use ($jobNo) {
                    $query->where('job_no', 'like', "%{$jobNo}%");
                })
                ->when($styleName, function ($query) use ($styleName) {
                    $query->where('style_name', $styleName);
                })
                ->when($factoryId, function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                })
                ->when($buyerId, function ($query) use ($buyerId) {
                    $query->where('buyer_id', $buyerId);
                })
                ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                    $query->whereBetween('costing_date', [$dateFrom, $dateTo]);
                })
                ->when($internalRefNo, function ($query) use ($internalRefNo) {
                    $query->where('internal_ref', $internalRefNo);
                })
                ->when($fileNo, function ($query) use ($fileNo) {
                    $query->where('file_no', $fileNo);
                })
                ->when($year, function ($query) use ($year) {
                    $query->whereYear('created_at', $year);
                })
                ->get();

            $fabric_costings = $fabric_costings->flatMap(function ($fabric_costing) use ($level, $uomId, $fabricNatureId, $fabricActionStatus) {
                if ($level == 1) {
                    $po = $fabric_costing->order->purchaseOrders->pluck('po_no')->unique()->implode(',');
                    if (isset($fabric_costing->fabricCosting->details['details']['fabricForm'])) {
                        return $this->formatSearchValue($fabric_costing->fabricCosting->details['details']['fabricForm'], $fabric_costing, $po, $level, $uomId, $fabricNatureId, $fabricActionStatus);
                    } else {
                        return [];
                    }
                } else {
                    return $fabric_costing->order->purchaseOrders->pluck('po_no')
                        ->unique()
                        ->flatMap(function ($po) use ($fabric_costing, $level, $uomId, $fabricNatureId, $fabricActionStatus) {
                            if (isset($fabric_costing->fabricCosting->details['details']['fabricForm'])) {
                                return $this->formatSearchValue($fabric_costing->fabricCosting->details['details']['fabricForm'], $fabric_costing, $po, $level, $uomId, $fabricNatureId, $fabricActionStatus);
                            } else {
                                return [];
                            }
                        });
                }
            });

            return response()->json([
                'request' => $request->all(),
                'data' => $fabric_costings,
                'message' => '',
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'request' => $request->all(),
                'data' => null,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function formatSearchValue($data, $fabric_costing, $po, $level, $uom, $fabricNatureId, $fabricActionStatus = null): Collection
    {
        $bookingFilters = new BookingFilters;

        $po_no = $level == 1 ? explode(',', $po) : (array)$po;
        $uomAndFabricNatureFilteredData = collect($data)
            ->when($uom, function ($query) use ($uom) {
                return $query->where('uom', $uom);
            })
            ->when($fabricNatureId, function ($query) use ($fabricNatureId) {
                return $query->where('fabric_nature_id', $fabricNatureId);
            });

        $hasMatchingSupplier = collect($uomAndFabricNatureFilteredData)->where('supplier_id', request('supplier_id'))->count();

        return $uomAndFabricNatureFilteredData
            ->filter(\Closure::fromCallable([$bookingFilters, 'filterFabricSourceWise']))
            ->filter($bookingFilters->filterSupplierWise($hasMatchingSupplier))
            ->map(function ($val) use ($fabric_costing, $po, $level, $po_no, $fabricActionStatus) {
                return [
                    'unique_id' => $fabric_costing->job_no,
                    'style_name' => $fabric_costing->style_name,
                    'po_no' => $po,
                    'is_approve' => $fabric_costing->is_approve ?? null,
                    'fabricActionStatus' => $fabricActionStatus,
                    'item_name' => $val['garment_item_name'] ?? '',
                    'item_id' => $val['garment_item_id'] ?? '',
                    'body_part_id' => $val['body_part_id'] ?? '',
                    'body_part_value' => $val['body_part_value'] ?? '',
                    'body_part_type' => $val['body_part_type'] ?? '',
                    'fabric_composition_id' => $val['fabric_composition_id'] ?? '',
                    'fabric_composition_value' => $val['fabric_composition_value'] ?? '',
                    'construction' => isset($val['fabric_composition_value']) ? explode(' [', $val['fabric_composition_value'])[0] : '',
                    'composition' => isset($val['fabric_composition_value']) ? str_replace(']', '', explode(' [', $val['fabric_composition_value'])[1]) : '',
                    'supplier_id' => $val['supplier_id'] ?? '',
                    'supplier_value' => $val['supplier_value'] ?? '',
                    'gsm' => $val['gsm'] ?? '',
                    'fabric_nature_id' => $val['fabric_nature_id'] ?? '',
                    'fabric_nature_value' => $val['fabric_nature_value'] ?? '',
                    'fabric_source' => $val['fabric_source'] ?? '',
                    'fabric_source_value' => $val['fabric_source_value'] ?? '',
                    'uom' => $val['uom'] ?? '',
                    'uom_value' => BudgetService::UOM[$val['uom']] ?? '',
                    'breakdown' => isset($val['greyConsForm']) ?
                        collect($val['greyConsForm']['details'])->whereIn('po_no', $po_no)->map(function ($breakdown) {
                            return $breakdown;
                        })->values() : [],
                    'level' => $level,
                    'color_type_id' => $val['color_type_id'],
                    'color_type_value' => $val['color_type_value'],
                    'dia_type' => $val['dia_type'],
                    'dia_type_value' => $val['dia_type_value'],
                    'code' => $val['code'] ?? '',
                ];
            });
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            foreach ($request->except('booking_id') as $data) {
                $data['details'] = $data['breakdown'];

                $data['booking_id'] = $request->booking_id;

                FabricBookingDetails::create($data);
            }
            DB::commit();

            return response()->json('Saved Successfully', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function oldData($id): JsonResponse
    {
        $data = FabricBookingDetails::where('booking_id', $id)->get();

        return response()->json($data, Response::HTTP_OK);
    }
}
