<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Samples;

use App\Http\Controllers\Controller;
use Excel;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Exports\SampleExcel;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\Merchandising\Services\SampleListFetchService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;

class SampleListController extends Controller
{
    public function index(Request $request)
    {
        try {
            $q = $request->all() ?? null;
            $paginateNumber = request('paginateNumber') ?? 15;
            $searchedSamples = 15;
            $samples = (new SampleListFetchService($q))->get($paginateNumber);
            $sampleStages = SampleRequisition::SAMPLE_STAGES;
            $buyers = $request->buyer_id ? Buyer::query()->where('id', $request->buyer_id)->pluck('name', 'id') : [];
            $productDepartments = $request->product_department_id ? ProductDepartments::query()->where('id', $request->product_department_id)->pluck('product_department', 'id') : [];
            $dealingMerchants = $request->dealing_merchant_id ? User::query()->where('id', $request->dealing_merchant_id)->pluck('screen_name', 'id') : [];
            $totalSampleRequisision = SampleRequisition::all()->count();
            $dashboardOverview = [
                "Total SampleRequisision" =>  $totalSampleRequisision 
            ];

            return view('merchandising::samples.index', [
                'samples' => $samples,
                'sample_stages' => $sampleStages,
                'buyers' => $buyers,
                'product_departments' => $productDepartments,
                'dealing_merchants' => $dealingMerchants,
                'dashboardOverview' =>  $dashboardOverview,
                'paginateNumber'    =>  $paginateNumber,
                'searchedSamples'    =>  $searchedSamples,

            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(["error" => $e->getMessage()]);
        }
    }
    public function SampleListExcelAll(Request $request)
    {
        $q = $request->all() ?? null;
        $samples = (new SampleListFetchService($q))->getAll();
        return Excel::download(new SampleExcel($samples), 'sample-list-all.xlsx');
    }

    //for View 1
    public function view($id)
    {
        try {
            $sampleRequisition = SampleRequisition::query()
                ->with([
                    'buyer',
                    'season',
                    'dealingMerchant',
                    'department',
                    'fabrics.gmtsItem',
                    'fabrics.unitOfMeasurement',
                    'accessories.garmentsItem',
                    'details'
                ])
                ->findOrFail($id);
            return view('merchandising::samples.view', compact('sampleRequisition'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function viewPdf($id)
    {
        try {
            $sampleRequisition = SampleRequisition::query()
                ->with(['buyer', 'season', 'dealingMerchant', 'department', 'fabrics.gmtsItem', 'fabrics.unitOfMeasurement', 'accessories.garmentsItem'])
                ->findOrFail($id);

            $signature = ReportSignatureService::getSignatures("SAMPLE VIEW", $sampleRequisition->buyer_id);

            $pdf = PDF::loadView('merchandising::samples.view-pdf', compact('sampleRequisition', 'signature'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('sample-view.pdf');
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    //for view 2

    public function viewV2($id)
    {
        try {
            $sampleRequisition = SampleRequisition::query()
                ->with([
                    'buyer',
                    'season',
                    'dealingMerchant',
                    'department',
                    'fabrics.gmtsItem',
                    'fabrics.unitOfMeasurement',
                    'accessories.garmentsItem',
                    'details'
                ])
                ->findOrFail($id);
            $sampleRequisition['viewNo'] = 2;
            return view('merchandising::samples.view', compact('sampleRequisition'));
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }

    public function viewPdfV2($id)
    {
        try {
            $sampleRequisition = SampleRequisition::query()
                ->with(['buyer', 'season', 'dealingMerchant', 'department', 'fabrics.gmtsItem', 'fabrics.unitOfMeasurement', 'accessories.garmentsItem'])
                ->findOrFail($id);
            $sampleRequisition['viewNo'] = 2;
            $signature = ReportSignatureService::getSignatures("SAMPLE VIEW", $sampleRequisition->buyer_id);

            $pdf = PDF::loadView('merchandising::samples.view-pdf', compact('sampleRequisition', 'signature'))
                ->setPaper('a4')->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('sample-view.pdf');
        } catch (Exception $exception) {
            return redirect()->back()->with(["error" => $exception->getMessage()]);
        }
    }
}
