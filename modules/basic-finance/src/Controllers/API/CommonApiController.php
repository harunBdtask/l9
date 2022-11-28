<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;
use SkylarkSoft\GoRMG\Finance\Models\FundRequisition;

class CommonApiController extends Controller
{
    public function fetchProjects(): JsonResponse
    {
        $projects = Project::query()->orderBy('project')->get(['project as text', 'id']);

        return response()->json($projects);
    }

    public function fetchUnits($id): JsonResponse
    {
        $units = Unit::query()->orderBy('unit')
            ->where('bf_project_id', $id)
            ->get(['unit as text', 'id']);

        return response()->json($units);
    }

    public function getRequisitionNo(): JsonResponse
    {
        $id = FundRequisition::query()->max('id');
        $req_id = sprintf("RQ-%06d", $id + 1);

        return response()->json($req_id);
    }

    public function getBFVouchersByBillNo(Request $request) : JsonResponse
    {
        try {
            $bfVoucherId = $request->get('id')??null;
            $billNo = trim($request->get('bill_no'))??null;
            $typeId = $request->get('type_id')??null;
            $result = Voucher::query()
                ->when($bfVoucherId, function ($query) use ($bfVoucherId){
                    $query->where('id', '!=', $bfVoucherId);
                })
                ->where('bill_no', $billNo)
                ->when($typeId, function($q) use($typeId){
                    return $q->where('type_id', $typeId);
                })
                
                ->get();
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
