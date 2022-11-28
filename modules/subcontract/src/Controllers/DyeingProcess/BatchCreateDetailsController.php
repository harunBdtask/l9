<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Subcontract\Actions\DyeingProcessActions\SyncBatchCreationAction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingBatchDetailsFormRequest;
use SkylarkSoft\GoRMG\Subcontract\Services\BatchNotifyService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BatchCreateDetailsController extends Controller
{
    /**
     * @param SubDyeingBatch $dyeingBatch
     * @return JsonResponse
     */
    public function getDetails(SubDyeingBatch $dyeingBatch): JsonResponse
    {
        try {
            $dyeingBatch->load('batchDetails');
            $batchDetails = $dyeingBatch->batchDetails->load('subTextileOrder')->map(function ($collection) {
                $criteria = [
                    'sub_textile_operation_id' => $collection->sub_textile_operation_id,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'color_id' => $collection->color_id,
                    'ld_no' => $collection->ld_no,
                    'color_type_id' => $collection->color_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'gsm' => $collection->gsm,
                    'unit_of_measurement_id' => $collection->unit_of_measurement_id,
                ];

                $prevBatchRoll = SubDyeingBatchDetail::query()->where($criteria)->sum('batch_roll');
                $prevBatchWeight = SubDyeingBatchDetail::query()->where($criteria)->sum('batch_weight');

                return [
                    'id' => $collection->id,
                    'sub_dyeing_batch_id' => $collection->sub_dyeing_batch_id,
                    'factory_id' => $collection->factory_id,
                    'supplier_id' => $collection->supplier_id,
                    'sub_textile_order_id' => $collection->sub_textile_order_id,
                    'sub_textile_order_no' => $collection->subTextileOrder->order_no,
                    'sub_textile_order_detail_id' => $collection->sub_textile_order_detail_id,
                    'sub_grey_store_id' => $collection->sub_grey_store_id,
                    'sub_dyeing_unit_id' => $collection->sub_dyeing_unit_id,
                    'sub_textile_operation_id' => $collection->sub_textile_operation_id,
                    'sub_textile_process_id' => $collection->sub_textile_process_id,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_composition_value' => $collection->fabric_composition_value,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'color_id' => $collection->color_id,
                    'color_name' => $collection->color->name,
                    'ld_no' => $collection->ld_no,
                    'color_type_id' => $collection->color_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'dia_type_value' => $collection->dia_type_value['name'],
                    'gsm' => $collection->gsm,
                    'material_description' => $collection->material_description,
                    'yarn_details' => $collection->yarn_details,
                    'grey_required_qty' => $collection->grey_required_qty,
                    'unit_of_measurement_id' => $collection->unit_of_measurement_id,
                    'stitch_length' => $collection->stitch_length,
                    'prev_batch_weight' => $prevBatchWeight,
                    'prev_batch_roll' => $prevBatchRoll,
                    'batch_roll' => $collection->batch_roll,
                    'issue_qty' => $collection->issue_qty,
                    'batch_weight' => $collection->batch_weight,
                ];
            });

            return response()->json([
                'message' => 'dyeing-batch-details fetched successfully',
                'data' => $batchDetails,
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
     * @param Request $request
     * @param SubDyeingBatch $dyeingBatch
     * @param SyncBatchCreationAction $syncBatchCreationAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(
        Request                 $request,
        SubDyeingBatch          $dyeingBatch,
        SyncBatchCreationAction $syncBatchCreationAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $dyeingBatch->batchDetails()->createMany($request->all());
            $syncBatchCreationAction->syncBatchFromBatchDetail($dyeingBatch);
            DB::commit();

            return response()->json([
                'message' => 'dyeing-batch-details created successfully',
                'data' => $dyeingBatch,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingBatchDetailsFormRequest $request
     * @param SubDyeingBatch $dyeingBatch
     * @param SubDyeingBatchDetail $batchDetail
     * @param SyncBatchCreationAction $syncBatchCreationAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(
        SubDyeingBatchDetailsFormRequest $request,
        SubDyeingBatch                   $dyeingBatch,
        SubDyeingBatchDetail             $batchDetail,
        SyncBatchCreationAction          $syncBatchCreationAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $batchDetail->fill($request->all())->save();
            $syncBatchCreationAction->syncBatchFromBatchDetail($dyeingBatch);
            DB::commit();

            $this->notify($dyeingBatch, 'updated');

            return response()->json([
                'message' => 'dyeing-batch-details updated successfully',
                'data' => $batchDetail,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param SubDyeingBatch $dyeingBatch
     * @param SubDyeingBatchDetail $batchDetail
     * @param SyncBatchCreationAction $syncBatchCreationAction
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(
        SubDyeingBatch          $dyeingBatch,
        SubDyeingBatchDetail    $batchDetail,
        SyncBatchCreationAction $syncBatchCreationAction
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $batchDetail->delete();
            $syncBatchCreationAction->syncBatchFromBatchDetail($dyeingBatch);
            DB::commit();

            $this->notify($batchDetail->subDyeingBatch, 'detail_deleted');

            return response()->json([
                'message' => 'dyeing-batch-details deleted successfully',
                'data' => [],
                'status' => Response::HTTP_NO_CONTENT,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function notify($data, $type)
    {
        (new BatchNotifyService())
            ->setData($data)
            ->setType($type)
            ->notify();
    }
}
