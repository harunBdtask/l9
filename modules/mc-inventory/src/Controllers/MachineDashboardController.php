<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use SkylarkSoft\GoRMG\McInventory\Models\McMaintenance;
use SkylarkSoft\GoRMG\McInventory\Models\MachineLocation;
use SkylarkSoft\GoRMG\McInventory\Models\McMachineTransfer;

class MachineDashboardController extends Controller
{
    public function index()
    {
        $factoryId = factoryId();
        $machineInFactory = McMachine::query()
                    ->count('status',1);
        $totalRunningMachine = McMachine::query()->where('status',1)->count('status',1);
        $totalIdleMachine = McMachine::query()->where('status',5)->count('status',5);
        $totalMachineTakenAsLoan = McMachine::query()->where('origin',1)->count();
        //dd($totalMachineTakenAsLoan);
        $totalMachineInLocations = MachineLocation::query()
            ->withCount('machine as totalMachineLocation')
            ->get();

        $servicePlanned = McMaintenance::query()->count('id');
        $actualMachineService = McMaintenance::query()->where('status',1)->count();

        return view('McInventory::machine-modules.machine-dashboard.machine-dashboard',[
            'machineInFactory' => $machineInFactory,
            'totalRunningMachine' => $totalRunningMachine,
            'totalIdleMachine' => $totalIdleMachine,
            'totalMachineTakenAsLoan' => $totalMachineTakenAsLoan,
            'totalMachineInLocations' => $totalMachineInLocations,
            'servicePlanned' => $servicePlanned,
            'actualMachineService' => $actualMachineService
        ]);
    }
}
