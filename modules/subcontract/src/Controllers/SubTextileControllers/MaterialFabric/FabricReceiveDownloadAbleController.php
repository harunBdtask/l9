<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceive;

class FabricReceiveDownloadAbleController extends Controller
{
    public function view($id)
    {
        $fabricReceive = SubGreyStoreReceive::query()->with([
            'factory',
            'supplier',
            'greyStore',
            'challanOrders.textileOrder',
            'receiveDetailsByChallanNo.textileOrder',
            'receiveDetailsByChallanNo.operation',
            'receiveDetailsByChallanNo.fabricComposition',
            'receiveDetailsByChallanNo.color',
            'receiveDetailsByChallanNo.colorType',
            'receiveDetailsByChallanNo.unitOfMeasurement',
        ])->findOrFail($id);
        $currentDate = Carbon::now()->format('Y-m-d');

        return view('subcontract::textile_module.material-fabric.receive.view', [
            'fabricReceive' => $fabricReceive,
            'currentDate' => $currentDate,
        ]);
    }

    public function pdf($id)
    {
        $fabricReceive = SubGreyStoreReceive::query()->with([
            'factory',
            'supplier',
            'greyStore',
            'challanOrders.textileOrder',
            'receiveDetailsByChallanNo.textileOrder',
            'receiveDetailsByChallanNo.operation',
            'receiveDetailsByChallanNo.fabricComposition',
            'receiveDetailsByChallanNo.color',
            'receiveDetailsByChallanNo.colorType',
            'receiveDetailsByChallanNo.unitOfMeasurement',
        ])->findOrFail($id);
        $currentDate = Carbon::now()->format('Y-m-d');

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.material-fabric.receive.pdf', [
                'fabricReceive' => $fabricReceive,
                'currentDate' => $currentDate,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream("{$id}_fabric_receive.pdf");
    }
}
