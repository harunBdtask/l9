<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\DyesStore\Exports\DyesAndChemicalReceiveReportExport;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;

class DyesAndChemicalReceiveReportController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::query()->pluck('name', 'id')
            ->prepend('Select', 0);
        return view('dyes-store::report.dyes_and_chemical_receive_report.index', [
            'suppliers' => $suppliers
        ]);
    }

    public function getReport(Request $request)
    {
        $supplier = $request->get('supplier_id');
        $fromDate = $request->get('from_date')
            ? Carbon::make($request->get('from_date'))->format('Y-m-d')
            : null;
        $toDate = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;

        $dyesChemicalReceive = DyesChemicalsReceive::query()
            ->with([
                'supplier'
            ])
            ->when($supplier, function (Builder $query) use ($supplier) {
                $query->where('supplier_id', $supplier);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                $query->whereBetween('receive_date', [$fromDate, $toDate]);
            })->get();
//        dd($dyesChemicalReceive);
        return view('dyes-store::report.dyes_and_chemical_receive_report.table', [
            'dyesChemicalReceive' => $dyesChemicalReceive
        ]);
    }

    public function pdf(Request $request)
    {
        $supplier = $request->get('supplier_id');
        $fromDate = $request->get('from_date')
            ? Carbon::make($request->get('from_date'))->format('Y-m-d')
            : null;
        $toDate = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;

        $dyesChemicalReceive = DyesChemicalsReceive::query()
            ->with([
                'supplier'
            ])
            ->when($supplier, function (Builder $query) use ($supplier) {
                $query->where('supplier_id', $supplier);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                $query->whereBetween('receive_date', [$fromDate, $toDate]);
            })->get();

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('dyes-store::report.dyes_and_chemical_receive_report.pdf', [
                'dyesChemicalReceive' => $dyesChemicalReceive
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('dyes_and_chemical_report.pdf');
    }

    public function excel(Request $request)
    {
        $supplier = $request->get('supplier_id');
        $fromDate = $request->get('from_date')
            ? Carbon::make($request->get('from_date'))->format('Y-m-d')
            : null;
        $toDate = $request->get('to_date') ?
            Carbon::make($request->get('to_date'))->format('Y-m-d')
            : null;

        $dyesChemicalReceive = DyesChemicalsReceive::query()
            ->with([
                'supplier'
            ])
            ->when($supplier, function (Builder $query) use ($supplier) {
                $query->where('supplier_id', $supplier);
            })
            ->when($fromDate && $toDate, function (Builder $query) use ($fromDate, $toDate) {
                $query->whereBetween('receive_date', [$fromDate, $toDate]);
            })->get();

        return Excel::download(new DyesAndChemicalReceiveReportExport($dyesChemicalReceive), 'dyes-and-chemical-report.xlsx');
    }

}
