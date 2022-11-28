<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssue;

class FabricIssueDownloadAbleController extends Controller
{
    public function view($id)
    {
        $fabricIssue = SubGreyStoreIssue::query()->with([
            'factory',
            'supplier',
            'textileOrder',
            'issueDetails.subTextileOperation',
            'issueDetails.subTextileProcess',
            'issueDetails.fabricComposition',
            'issueDetails.fabricType',
            'issueDetails.color',
            'issueDetails.colorType',
            'issueDetails.unitOfMeasurement',
            'subGreyStore',
            'subDyeingUnit',
        ])->findOrFail($id);

        $currentDate = Carbon::now()->format('Y-m-d');

        return view('subcontract::textile_module.material-fabric.issue.view', [
            'fabricIssue' => $fabricIssue,
            'currentDate' => $currentDate,
        ]);
    }

    public function pdf($id)
    {
        $fabricIssue = SubGreyStoreIssue::query()->with([
            'factory',
            'supplier',
            'textileOrder',
            'issueDetails.subTextileOperation',
            'issueDetails.subTextileProcess',
            'issueDetails.fabricComposition',
            'issueDetails.fabricType',
            'issueDetails.color',
            'issueDetails.colorType',
            'issueDetails.unitOfMeasurement',
            'subGreyStore',
            'subDyeingUnit',
        ])->findOrFail($id);
        $currentDate = Carbon::now()->format('Y-m-d');
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.material-fabric.issue.pdf', [
                'fabricIssue' => $fabricIssue,
                'currentDate' => $currentDate,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("{$id}_fabric_issue.pdf");
    }
}
