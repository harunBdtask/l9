<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Finishingdroplets\ValueObjects\FinishingProductionValueObject;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class FinishingProductionReportV3Controller extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->get()->pluck('name', 'id');
        $finishFloors = FinishingFloor::query()->get()->pluck('name', 'id');
        $sewingFloors = Floor::query()->get()->pluck('floor_no', 'id');

        return view('finishingdroplets::reports.finishing-production-report-v3', [
            'buyers' => $buyers,
            'finishFloors' => $finishFloors,
            'sewingFloors' => $sewingFloors,
        ]);
    }

    public function report(Request $request)
    {
        $from = $request->input('from', date('Y-m-d'));
        $to = $request->input('to', date('Y-m-d'));
        $buyerId = $request->input('buyer_id');
        $finishFloorId = $request->input('finish_floor_id');
        $sewingFloorId = $request->input('sewing_floor_id');

        $reports = (new FinishingProductionValueObject())
            ->setFrom($from)->setTo($to)
            ->setBuyerId($buyerId)
            ->setFinishingFloor($finishFloorId)
            ->setSewingFloor($sewingFloorId)
            ->report();

        return view('finishingdroplets::reports.tables.finishing-production-report-v3-table', [
            'productions' => $reports,
        ]);
    }
}
