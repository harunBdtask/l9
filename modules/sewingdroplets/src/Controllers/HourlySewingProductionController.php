<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use Symfony\Component\HttpFoundation\Response;

class HourlySewingProductionController extends Controller
{
    public function hourlySewingProductionAgainstTargetData(Request $request)
    {
        $data['target_data'] = [];
        $data['production_data'] = [];
        try {
            $date = $request->date ?? date('Y-m-d');
            $hours = ['hour_8', 'hour_9', 'hour_10', 'hour_11', 'hour_12', 'hour_14', 'hour_15', 'hour_16', 'hour_17', 'hour_18'];

            $hourlySewingTarget = SewingLineTarget::query()
                ->where('target_date', $date)
                ->sum('target');

            $hourlySewingProductionQuery = HourlySewingProductionReport::query()
                ->select('hour_8', 'hour_9', 'hour_10', 'hour_11', 'hour_12', 'hour_13', 'hour_14', 'hour_15', 'hour_16', 'hour_17', 'hour_18')
                ->where('production_date', $date)
                ->get();
            foreach($hours as $key => $hour) {
                $data['target_data'][++$key] = $hourlySewingTarget;
                $data['production_data'][++$key] = $hour != 'hour_12' ? $hourlySewingProductionQuery->sum($hour) : ($hourlySewingProductionQuery->sum($hour) + $hourlySewingProductionQuery->sum('hour_13'));
            }
            $message = \SUCCESS_MSG;
            $status = Response::HTTP_OK;
        } catch (Exception $e) {
            $message = \SOMETHING_WENT_WRONG;
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $error = $e->getMessage();
        }
        
        return response()->json([
            'data' => $data,
            'status' => $status,
            'message' => $message,
            'error' => $error ?? null,
        ]);
    }
}
