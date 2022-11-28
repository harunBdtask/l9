<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class DailySewingOutputReport implements FromView, ShouldAutoSize
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        // TODO: Implement view() method.
        $data = self::data($this->request);
        $metaData = self::metaData($this->request);
        return view('manual-production::reports.dailySewingOutputReport.data', compact(
            'data',
            'metaData'
        ));
    }

    public static function data($request)
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $orderId = $request->get('order_id');
        $colorId = $request->get('color_id');

        return ManualDateWiseSewingReport::query()
            ->with('floor:id,floor_no', 'line:id,line_no')
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->where('order_id', $orderId)
            ->when('color_id', function ($q) use ($colorId){
                $q->where('color_id', $colorId);
            })
            ->whereNotNull('floor_id')
            ->whereNotNull('line_id')
            ->get();
    }

    public static function metaData($request): array
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $orderId = $request->get('order_id');
        $colorId = $request->get('color_id');

        $metadata['factory'] = Factory::query()->find($factoryId)['factory_name'] ?? null;
        $metadata['buyer'] = Buyer::query()->find($buyerId)['name'] ?? null;
        $metadata['order'] = Order::query()->find($orderId)['style_name'] ?? null;
        $metadata['color'] = Color::query()->find($colorId)['name'] ?? 'N/A';

        return $metadata;
    }
}
