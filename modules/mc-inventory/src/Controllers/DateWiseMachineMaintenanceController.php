<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\McInventory\Models\McMaintenance;

class DateWiseMachineMaintenanceController extends Controller
{
    public function getData()
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $maintenances = McMaintenance::query()
            ->with([
                'machine',
                'machineUnit'
            ])
            ->whereDate('next_maintenance',$todayDate)
            ->get();

        return view('McInventory::machine-modules.maintenance-calender.date-wise-maintenance-calender',[
            'maintenances' => $maintenances
        ]);
    }
}
