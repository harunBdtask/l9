<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Excel;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\McInventory\Exports\MaintenanceCalenderExport;
use SkylarkSoft\GoRMG\McInventory\Models\MachineUnit;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use SkylarkSoft\GoRMG\McInventory\Models\McMaintenance;


class MaintenanceCalenderController extends Controller
{
    public function index()
    {
        $month = [];
        for ($i = 1; $i <= 12; $i++) {
            $month[date('m', strtotime('+' . $i . ' month'))] = date('F', strtotime('+' . $i . ' month'));
        }
        //return $month;
        $machineUnits = MachineUnit::query()->pluck('name', 'id')->prepend('Select', '0');
        return view('McInventory::machine-modules.maintenance-calender.index', [
            'machineUnits' => $machineUnits,
            'month' => $month
        ]);
    }

    public function getMaintenance(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $unit = $request->get('unit');
        $maintenances = McMachine::query()
            ->with([
                'unit'
            ])
            ->when($year, function (Builder $query) use ($year) {
                $query->whereYear('last_maintenance', $year)
                    ->whereYear('next_maintenance', $year);
            })
            ->when($month, function (Builder $query) use ($month) {
                $query->whereMonth('next_maintenance', $month);
            })
            ->when($unit, function (Builder $query) use ($unit) {
                $query->where('unit_id', $unit);
            })
            ->get();
        return view('McInventory::machine-modules.maintenance-calender.maintenance-calender-table', [
            'maintenances' => $maintenances
        ]);
    }

    public function pdf(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $unit = $request->get('unit');
        $maintenances = McMachine::query()
            ->with([
                'unit'
            ])
            ->when($year, function (Builder $query) use ($year) {
                $query->whereYear('last_maintenance', $year)
                    ->whereYear('next_maintenance', $year);
            })
            ->when($month, function (Builder $query) use ($month) {
                $query->whereMonth('next_maintenance', $month);
            })
            ->when($unit, function (Builder $query) use ($unit) {
                $query->where('unit_id', $unit);
            })
            ->get();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('McInventory::machine-modules.maintenance-calender.pdf', [
                'maintenances' => $maintenances,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('maintenance_calender.pdf');
    }

    public function excel(Request $request)
    {
        $year = $request->get('year');
        $month = $request->get('month');
        $unit = $request->get('unit');

        $maintenances = McMachine::query()
            ->with([
                'unit'
            ])
            ->when($year, function (Builder $query) use ($year) {
                $query->whereYear('last_maintenance', $year)
                    ->whereYear('next_maintenance', $year);
            })
            ->when($month, function (Builder $query) use ($month) {
                $query->whereMonth('next_maintenance', $month);
            })
            ->when($unit, function (Builder $query) use ($unit) {
                $query->where('unit_id', $unit);
            })
            ->get();

        return Excel::download(new MaintenanceCalenderExport($maintenances), 'maintenance_calender.xlsx');
    }

}
