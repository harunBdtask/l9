<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Reports;

use App\Http\Controllers\Controller;
use PDF;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\ManualProduction\Exports\CuttingProductionReportExport;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualCuttingProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CuttingReportController extends Controller
{
    public function dateWiseCuttingProductionReport(Request $request)
    {
        try {
            $factory_options = Factory::query()->pluck('factory_name', 'id');
            $factory_id = $request->factory_id ?? null;
            $subcontract_factory_id = $request->subcontract_factory_id ?? null;
            $subcontract_factory_options = $subcontract_factory_id ? SubcontractFactoryProfile::query()->where('id', $subcontract_factory_id)->pluck('name', 'id') : [];
            $date = $request->date ?? date('Y-m-d');
            $reports = $this->getDateWiseCuttingProductionReport($date, $factory_id, $subcontract_factory_id);
            return view('manual-production::reports.cutting.date_wise_cutting_report', [
                'factory_options' => $factory_options,
                'factory_id' => $factory_id,
                'subcontract_factory_id' => $subcontract_factory_id,
                'subcontract_factory_options' => $subcontract_factory_options,
                'date' => $date,
                'reports' => $reports,
            ]);
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }

    private function getDateWiseCuttingProductionReport($date, $factory_id = '', $subcontract_factory_id = '')
    {
        return ManualCuttingProduction::query()
            ->where('production_date', $date)
            ->when($factory_id == '' && $subcontract_factory_id == '', function ($query) use ($factory_id) {
                $query->where('factory_id', factoryId());
            })
            ->when($factory_id != '' && $subcontract_factory_id == '', function ($query) use ($factory_id) {
                $query->where('factory_id', $factory_id);
            })
            ->when($subcontract_factory_id != '', function ($query) use ($subcontract_factory_id) {
                $query->where('subcontract_factory_id', $subcontract_factory_id);
            })
            ->where('production_qty', '>', 0)
            ->get();
    }

    public function dateWiseCuttingProductionReportPdf(Request $request)
    {
        try {
            $factory_options = Factory::query()->pluck('factory_name', 'id');
            $factory_id = $request->factory_id ?? null;
            $subcontract_factory_id = $request->subcontract_factory_id ?? null;
            $subcontract_factory_options = $subcontract_factory_id ? SubcontractFactoryProfile::query()->where('id', $subcontract_factory_id)->pluck('name', 'id') : [];
            $date = $request->date ?? date('Y-m-d');
            $reports = $this->getDateWiseCuttingProductionReport($date, $factory_id, $subcontract_factory_id);
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('manual-production::reports.cutting.date_wise_cutting_report_pdf', [
                'factory_options' => $factory_options,
                'factory_id' => $factory_id,
                'subcontract_factory_id' => $subcontract_factory_id,
                'subcontract_factory_options' => $subcontract_factory_options,
                'date' => $date,
                'reports' => $reports,
            ])->setPaper('a4')->setOrientation('landscape');
            return $pdf->stream('cutting_production_report.pdf');
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }

    public function dateWiseCuttingProductionReportExcel(Request $request)
    {
        try {
            $factory_options = Factory::query()->pluck('factory_name', 'id');
            $factory_id = $request->factory_id ?? null;
            $subcontract_factory_id = $request->subcontract_factory_id ?? null;
            $subcontract_factory_options = $subcontract_factory_id ? SubcontractFactoryProfile::query()->where('id', $subcontract_factory_id)->pluck('name', 'id') : [];
            $date = $request->date ?? date('Y-m-d');
            $reports = $this->getDateWiseCuttingProductionReport($date, $factory_id, $subcontract_factory_id);
            return Excel::download(new CuttingProductionReportExport($reports, $subcontract_factory_id), 'cutting_production.xlsx');
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }
}
