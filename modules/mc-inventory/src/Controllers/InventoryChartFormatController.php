<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;
use PDF;
use Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use SkylarkSoft\GoRMG\McInventory\Models\MachineType;
use SkylarkSoft\GoRMG\McInventory\Models\MachineUnit;
use SkylarkSoft\GoRMG\McInventory\Models\MachineLocation;
use SkylarkSoft\GoRMG\McInventory\Exports\InventoryChartFormatExport;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class InventoryChartFormatController extends Controller
{
    public function index()
    {

        $items = MachineType::with('machineSubType')->withCount('machine')->get();
        $units = MachineUnit::with('machine')->get();
        $locations = MachineLocation::with('machine')->get();
        $machineProfile = McMachine::query()->get();
        $loan_given_origin = array_search('Loan', McMachineInventoryConstant::MACHINE_ORIGINS);
        $loan_taken_origin = array_search('Rental', McMachineInventoryConstant::MACHINE_ORIGINS);

        return view('McInventory::machine-modules.machine-chart-format.machine-chart-format',
            compact('items', 'units', 'locations','loan_given_origin','loan_taken_origin','machineProfile'));
    }

    public function machineChartFormatPDF()
    {
        $items = MachineType::with('machineSubType')->withCount('machine')->get();
        $units = MachineUnit::with('machine')->get();
        $locations = MachineLocation::with('machine')->get();
        $machineProfile = McMachine::query()->get();
        $loan_given_origin = array_search('Loan', McMachineInventoryConstant::MACHINE_ORIGINS);
        $loan_taken_origin = array_search('Rental', McMachineInventoryConstant::MACHINE_ORIGINS);

        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('McInventory::machine-modules.machine-chart-format.machine-chart-format-pdf', [
            'items' => $items,
            'units' => $units,
            'locations' => $locations,
            'machineProfile' => $machineProfile,
            'loan_given_origin' => $loan_given_origin,
            'loan_taken_origin' => $loan_taken_origin,
        ])->setPaper('a4')->setOrientation('landscape')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('batch_report.pdf');
    }

    public function machineChartFormatExcel()
    {
        $items = MachineType::with('machineSubType')->withCount('machine')->get();
        $units = MachineUnit::with('machine')->get();
        $locations = MachineLocation::with('machine')->get();
        $machineProfile = McMachine::query()->get();
        $loan_given_origin = array_search('Loan', McMachineInventoryConstant::MACHINE_ORIGINS);
        $loan_taken_origin = array_search('Rental', McMachineInventoryConstant::MACHINE_ORIGINS);

        return Excel::download(new InventoryChartFormatExport($items,$units,$locations,$loan_given_origin,$loan_taken_origin,$machineProfile),
            'inventory_chart_format.xlsx');
    }
}
