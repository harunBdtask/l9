<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Services\PriceQuotationViewService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;

class PriceQuotationReportController extends Controller
{
    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function view($id)
    {
        $data = PriceQuotationViewService::data($id);
        $signature = ReportSignatureService::getApprovalSignature(PriceQuotation::class, $id);
        return view("merchandising::price_quotation.view", $data, ["signature" => $signature]);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */

    public function print($id)
    {
        $data = PriceQuotationViewService::data($id);
        $signature = ReportSignatureService::getApprovalSignature(PriceQuotation::class, $id);
        return view("merchandising::price_quotation.print", $data, ['signature' => $signature]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function pdf($id): Response
    {
        $data = PriceQuotationViewService::data($id);
        $signature = ReportSignatureService::getSignatures(PriceQuotation::class, $id);
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView("merchandising::price_quotation.pdf", $data,
            ['signature' => $signature])
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->download("{$id}_price_quotation.pdf");
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function costingView($id)
    {
        $data = PriceQuotationViewService::costingData($id);
//        dd($data['priceQuotation']->toArray());
        return view("merchandising::price_quotation.costing_view", $data);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function costingPrint($id)
    {
        $data = PriceQuotationViewService::costingData($id);
        $signature = ReportSignatureService::getSignatures(PriceQuotation::class, $id);
        return view("merchandising::price_quotation.costing_print", $data, ['signature' => $signature]);
    }

    /**
     * @param $id
     * @return Response
     */
    public function costingPDF($id): Response
    {
        $data = PriceQuotationViewService::costingData($id);
        $signature = ReportSignatureService::getApprovalSignature(PriceQuotation::class, $id);
        $pdf = PDF::loadView("merchandising::price_quotation.costing_pdf", $data, ['signature' => $signature])
            ->setOptions([
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);
        return $pdf->download("{$id}_price_quotation_costing.pdf");
    }

    public function viewForAnwar($id)
    {
        $data = PriceQuotationViewService::formatAnwarViewData($id);
//        $signature = ReportSignatureService::getSignatures("PRICE QUOTATION VIEW", $data['price_quotation']['buyer_id']);
        return view("merchandising::price_quotation.anwar.view", $data);
    }

    public function pdfAnwar($id)
    {
        $data = PriceQuotationViewService::formatAnwarViewData($id);
//        $signature = ReportSignatureService::getSignatures("PRICE QUOTATION VIEW", $data['price_quotation']['buyer_id']);
        $pdf = PDF::setOption('enable-local-file-access', true)->loadView("merchandising::price_quotation.anwar.pdf", $data)
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("{$id}_price_quotation.pdf");
    }
}
