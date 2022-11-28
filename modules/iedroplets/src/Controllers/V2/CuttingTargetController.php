<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers\V2;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Iedroplets\Models\CuttingTarget;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use Symfony\Component\HttpFoundation\Response;

class CuttingTargetController extends Controller
{
    public function index()
    {
        return view(PackageConst::PACKAGE_NAME . "::forms.v2.date_wise_cutting_target");
    }

    public function fetchCuttingTarget(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $target_date = $request->get('target_date', now()->toDateString());
            $cutting_floor_id = $request->get('cutting_floor_id');
            $data = $this->fetchCuttingTargetData($target_date, $cutting_floor_id);

            $response = [
                'data' => $data ?? [],
                'status' => Response::HTTP_OK,
                'message' => SUCCESS_MSG,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => SOMETHING_WENT_WRONG,
                'error' => $e->getMessage(),
            ];
        } finally {
            return response()->json($response, $response['status']);
        }
    }

    private function fetchCuttingTargetData($target_date, $cutting_floor_id = null): array
    {
        $data = [
            'cutting_floor_name' => [],
            'cutting_floor_id' => [],
            'cutting_table_name' => [],
            'cutting_table_id' => [],
            'garments_item_group_id' => [],
            'garments_item_id' => [],
            'is_manual' => [],
            'mp' => [],
            'wh' => [],
            'total_working_minutes' => [],
            'smv' => [],
            'req_efficiency' => [],
            'target' => [],
            'hourly_target' => [],
            'remarks' => [],
        ];
        $cutting_tables = CuttingTable::withoutGlobalScope('factoryId')
            ->leftjoin('cutting_floors', 'cutting_tables.cutting_floor_id', 'cutting_floors.id')
            ->leftjoin('cutting_targets', 'cutting_targets.cutting_table_id', 'cutting_tables.id')
            ->where('cutting_targets.target_date', $target_date)
            ->when($cutting_floor_id, function ($query) use ($cutting_floor_id) {
                $query->where('cutting_tables.cutting_floor_id', $cutting_floor_id);
            })
            ->where('cutting_tables.factory_id', factoryId())
            ->select([
                'cutting_targets.*',
                'cutting_floors.floor_no',
                'cutting_floors.id as cutting_floor_id',
                'cutting_tables.table_no',
                'cutting_tables.id as table_id',
            ])
            ->orderBy('cutting_floors.floor_no')
            ->orderBy('cutting_tables.table_no')
            ->get();

        if ($cutting_tables->count() == 0) {
            $cutting_tables = CuttingTable::withoutGlobalScope('factoryId')
                ->leftjoin('cutting_floors', 'cutting_tables.cutting_floor_id', 'cutting_floors.id')
                ->when($cutting_floor_id, function ($query) use ($cutting_floor_id) {
                    $query->where('cutting_tables.cutting_floor_id', $cutting_floor_id);
                })
                ->where('cutting_tables.factory_id', factoryId())
                ->select([
                    'cutting_floors.floor_no',
                    'cutting_floors.id as cutting_floor_id',
                    'cutting_tables.table_no',
                    'cutting_tables.id as table_id'
                ])
                ->orderBy('cutting_floors.floor_no')
                ->orderBy('cutting_tables.table_no')
                ->get();
        }

        foreach ($cutting_tables as $target) {
            $data['cutting_floor_name'][] = $target->floor_no;
            $data['cutting_floor_id'][] = $target->cutting_floor_id;
            $data['cutting_table_name'][] = $target->table_no;
            $data['cutting_table_id'][] = $target->table_id;
            $data['garments_item_group_id'][] = $target->garments_item_group_id ?? null;
            $data['garments_item_id'][] = $target->garments_item_id ?? null;
            $data['is_manual'][] = $target->is_manual ?? null;
            $data['mp'][] = $target->mp ?? null;
            $data['wh'][] = $target->wh ?? null;
            $data['total_working_minutes'][] = $target->total_working_minutes ?? null;
            $data['smv'][] = $target->smv ?? null;
            $data['req_efficiency'][] = $target->req_efficiency ?? null;
            $data['target'][] = $target->target ?? null;
            $data['hourly_target'][] = $target->hourly_target ?? null;
            $data['remarks'][] = $target->remarks ?? null;
        }

        return $data;
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $targets = $request->get('target');
        $hourly_targets = $request->get('hourly_target');
        $cutting_table_ids = $request->get('cutting_table_id');
        $cutting_floor_ids = $request->get('cutting_floor_id');
        $garments_item_group_ids = $request->get('garments_item_group_id');
        $garments_item_ids = $request->get('garments_item_id');
        $is_manuals = $request->get('is_manual');
        $mps = $request->get('mp');
        $whs = $request->get('wh');
        $total_working_minutes = $request->get('total_working_minutes');
        $smvs = $request->get('smv');
        $req_efficiencies = $request->get('req_efficiency');
        $remarks = $request->get('remarks');

        if (array_sum($targets) == 0) {
            $message = 'Please fill up at least one row';
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            return \response()->json([
                'message' => $message,
                'status' => $status
            ], $status);
        }

        try {
            DB::beginTransaction();

            $today = date('Y-m-d');
            $count = 0;
            $insert_rows = count($cutting_table_ids);
            $cutting_targets = CuttingTarget::query()
                ->whereIn('cutting_table_id', $cutting_table_ids)
                ->where('target_date', $today)->get();

            for ($i = 0; $i < $insert_rows; $i++) {

                $cutting_target = [
                    'cutting_floor_id' => $cutting_floor_ids[$i],
                    'cutting_table_id' => $cutting_table_ids[$i],
                    'garments_item_group_id' => $garments_item_group_ids[$i] ?? null,
                    'garments_item_id' => $garments_item_ids[$i] ?? null,
                    'is_manual' => $is_manuals[$i] ?? null,
                    'mp' => $mps[$i] ?? 0,
                    'wh' => $whs[$i] ?? 0,
                    'total_working_minutes' => $total_working_minutes[$i] ?? 0,
                    'smv' => $smvs[$i] ?? 0,
                    'req_efficiency' => $req_efficiencies[$i] ?? 0,
                    'target_date' => $today,
                    'target' => $targets[$i] ?? 0,
                    'hourly_target' => $hourly_targets[$i] ?? 0,
                    'remarks' => $remarks[$i] ?? null,
                ];

                $count = $cutting_targets->where('cutting_table_id', $cutting_table_ids[$i])->count();

                if ($count > 0) {
                    CuttingTarget::query()
                        ->where([
                            'cutting_table_id' => $cutting_table_ids[$i],
                            'target_date' => $today
                        ])->update($cutting_target);

                } else {
                    CuttingTarget::Create($cutting_target);
                }

                $count++;
            }

            if ($count > 0) {
                $response = [
                    'message' => S_UPDATE_MSG,
                    'status' => Response::HTTP_OK,
                ];
            } else {
                $response = [
                    'message' => 'Please fill up at least one row',
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            $response = [
                'message' => SOMETHING_WENT_WRONG,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $e->getMessage()
            ];
        } finally {
            return response()->json($response, $response['status']);
        }
    }
}
