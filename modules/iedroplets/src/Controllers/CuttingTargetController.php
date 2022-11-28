<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Iedroplets\Models\CuttingTarget;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;

class CuttingTargetController extends Controller
{
    public function index()
    {
        $cutting_targets = CuttingTarget::orderBy('id', 'DESC')->paginate();

        return view(PackageConst::PACKAGE_NAME . '::pages.cutting_targets',
            [
                'cutting_targets' => $cutting_targets,
            ]);
    }

    public function create()
    {
        $floors = CuttingFloor::pluck('floor_no', 'id')->all();
        return view(PackageConst::PACKAGE_NAME . '::forms.cutting_target',
            [
                'cutting_target' => null,
                'floors' => $floors,
            ]);
    }

    public function edit($id)
    {
        $floors = CuttingFloor::pluck('floor_no', 'id')->all();
        $cutting_target = CuttingTarget::findOrFail($id);

        return view(PackageConst::PACKAGE_NAME . '::forms.cutting_target',
            [
                'cutting_target' => $cutting_target,
                'floors' => $floors,
            ]);
    }

    public function destroy($id)
    {
        $cutting_target = CuttingTarget::findOrFail($id);
        $cutting_target->delete();

        return redirect('/cutting-targets');
    }

    public function dateWiseCuttingTargets()
    {
        $cutting_floors = CuttingFloor::pluck('floor_no', 'id')->all();

        return view(PackageConst::PACKAGE_NAME . '::forms.date_wise_cutting_target',
            [
                'cutting_floors' => $cutting_floors,
            ]);
    }

    public function dateWiseCuttingTargetsFrom($cutting_floor_id): \Illuminate\Http\JsonResponse
    {
        // minimise query when free
        $cutting_tables = CuttingTable::withoutGlobalScope('factoryId')
            ->leftjoin('cutting_floors', 'cutting_tables.cutting_floor_id', 'cutting_floors.id')
            ->leftjoin('cutting_targets', 'cutting_targets.cutting_table_id', 'cutting_tables.id')
            ->where('cutting_targets.target_date', date('Y-m-d'))
            ->where('cutting_tables.cutting_floor_id', $cutting_floor_id)
            ->where('cutting_tables.factory_id', factoryId())
            ->select(
                'cutting_targets.*',
                'cutting_floors.floor_no',
                'cutting_floors.id as cutting_floor_id',
                'cutting_tables.table_no',
                'cutting_tables.id as table_id'
            )
            ->orderBy('cutting_floors.floor_no', 'asc')
            ->orderBy('cutting_tables.table_no', 'asc')
            ->get();

        if (count($cutting_tables) == 0) {
            $cutting_tables = CuttingTable::withoutGlobalScope('factoryId')
                ->leftjoin('cutting_floors', 'cutting_tables.cutting_floor_id', 'cutting_floors.id')
                ->where('cutting_tables.cutting_floor_id', $cutting_floor_id)
                ->where('cutting_tables.factory_id', factoryId())
                ->select(
                    'cutting_floors.floor_no',
                    'cutting_floors.id as cutting_floor_id',
                    'cutting_tables.table_no',
                    'cutting_tables.id as table_id'
                )
                ->orderBy('cutting_floors.floor_no', 'asc')
                ->orderBy('cutting_tables.table_no', 'asc')
                ->get();
        }

        $view = view(PackageConst::PACKAGE_NAME . '::forms.date_wise_cutting_target_form',
            compact('cutting_tables')
        )->render();

        return response()->json(['view' => $view]);
    }


    /**
     * query will be updated when free
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function dateWiseCuttingTargetsPost(Request $request): \Illuminate\Http\RedirectResponse
    {
        $target = $request->get('target');
        $table_id = $request->get('table_id');
        $cutting_floor_id = $request->get('cutting_floor_id');
        $mp = $request->get('mp');
        $wh = $request->get('wh');
        $npt = $request->get('npt');

        if (array_sum($target) == 0) {
            Session::flash('error', 'Please fill up at least one row');
            return redirect()->back();
        }

        try {
            $today = date('Y-m-d');
            $count = 0;

            DB::beginTransaction();

            $insert_rows = count($table_id);

            $cutting_target_ids = CuttingTarget::query()
                ->whereIn('cutting_table_id', $table_id)
                ->where('target_date', $today)
                ->get();

            for ($i = 0; $i < $insert_rows; $i++) {
                $cutting_target = [
                    'cutting_floor_id' => $cutting_floor_id[$i],
                    'cutting_table_id' => $table_id[$i],
                    'target' => $target[$i] ?? 0,
                    'mp' => $mp[$i] ?? 0,
                    'wh' => $wh[$i] ?? 0,
                    'npt' => $npt[$i] ?? 0,
                    'target_date' => $today
                ];

                $count = $cutting_target_ids->where('cutting_table_id', $table_id[$i])->count();

                if ($count > 0) {
                    $count++;
                    CuttingTarget::where(['cutting_table_id' => $table_id[$i], 'target_date' => $today])
                        ->update($cutting_target);
                } else {
                    $count++;
                    CuttingTarget::create($cutting_target);
                }
            }

            if ($count > 0) {
                Session::flash('success', S_UPDATE_MSG);
            } else {
                Session::flash('error', 'Please fill up at least one row');
            }

            DB::commit();
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
