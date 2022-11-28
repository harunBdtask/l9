<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers;

use App\Http\Controllers\Controller;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;

class SubTextileOrderDownloadController extends Controller
{
    public function view($id)
    {
        $orderDetails = SubTextileOrder::query()->with([
            'factory',
            'supplier',
            'currency',
            'subTextileOrderDetails.subTextileOperation',
            'subTextileOrderDetails.subTextileProcess',
            'subTextileOrderDetails.fabricType',
            'subTextileOrderDetails.color',
            'subTextileOrderDetails.colorType',
            'subTextileOrderDetails.unitOfMeasurement',
            'subTextileOrderDetails.bodyPart',
        ])->findOrFail($id);

        return view(PackageConst::VIEW_PATH . 'textile_module.order_management.view', [
            'orderDetails' => $orderDetails,
        ]);
    }

    public function pdf($id)
    {
        $orderDetails = SubTextileOrder::query()->with([
            'factory',
            'supplier',
            'currency',
            'subTextileOrderDetails.subTextileOperation',
            'subTextileOrderDetails.subTextileProcess',
            'subTextileOrderDetails.fabricType',
            'subTextileOrderDetails.color',
            'subTextileOrderDetails.colorType',
            'subTextileOrderDetails.unitOfMeasurement',
            'subTextileOrderDetails.bodyPart',
        ])->findOrFail($id);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView(PackageConst::VIEW_PATH . 'textile_module.order_management.pdf', [
                'orderDetails' => $orderDetails,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("{$id}_order_details.pdf");
    }
}
