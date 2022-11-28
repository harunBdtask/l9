<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inputdroplets\DTO\InputDashboardV2DTO;

class InputDashboardV2Controller extends Controller
{

    public function __invoke(Request $request)
    {
        $date = $request->get('date') ?? date('Y-m-d');
        $floorNo = $request->get('floor_no');

        $inputDashboardV2DTO = new InputDashboardV2DTO();
        $inputDashboardV2DTO->setDate($date);
        $inputDashboardV2DTO->setFloorId($floorNo);
        $report = $inputDashboardV2DTO->getReport();

        return view('inputdroplets::reports.input_dashboard_v2', ['report' => $report]);
    }
}
