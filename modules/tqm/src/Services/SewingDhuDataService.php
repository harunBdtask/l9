<?php

namespace SkylarkSoft\GoRMG\TQM\Services;


use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\TQM\Models\TqmSewingDhu;

class SewingDhuDataService
{
    private $factoryId, $sewingDate, $sewingFloorId;

    public function __construct($request)
    {
        $this->factoryId = $request->get('factory_id') ?? factoryId();
        $this->sewingDate = $request->get('sewing_date');
        $this->sewingFloorId = $request->get('sewing_floor_id');
    }

    public function getData()
    {
        $sewingProductions = HourlySewingProductionReport::query()
            ->select('line_id', 'buyer_id', 'order_id', 'purchase_order_id')
            ->with(['buyer:id,name', 'order:id,style_name', 'purchaseOrder:id,po_no', 'line' => function ($q) {
                $q->select('id', 'line_no', 'sort')->orderBy('sort');
            }])
            ->where('factory_id', $this->factoryId)
            ->whereDate('production_date', $this->sewingDate)
            ->where('floor_id', $this->sewingFloorId)
            ->groupBy('line_id', 'buyer_id', 'order_id', 'purchase_order_id')
            ->get();

        $arraysForQuery = [
            'lineIdsArr' => $sewingProductions->pluck('line_id')->unique()->values(),
            'buyerIdsArr' => $sewingProductions->pluck('buyer_id')->unique()->values(),
            'orderIdsArr' => $sewingProductions->pluck('order_id')->unique()->values(),
            'purchaseOrderIdsArr' => $sewingProductions->pluck('purchase_order_id')->unique()->values(),
        ];

        $existingSewingDhu = $this->existingSewingDhus($arraysForQuery);

        return (new DhuDataFormatter())
            ->setData($sewingProductions)
            ->setDhu($existingSewingDhu)
            ->setProductionDate($this->sewingDate)
            ->setType('sewing')
            ->format();
    }

    private function existingSewingDhus($data)
    {
        return TqmSewingDhu::query()
            ->with('details.tqmDefect')
            ->whereIn('line_id', $data['lineIdsArr'])
            ->whereIn('buyer_id', $data['buyerIdsArr'])
            ->whereIn('order_id', $data['orderIdsArr'])
            ->whereIn('purchase_order_id', $data['purchaseOrderIdsArr'])
            ->where('factory_id', $this->factoryId)
            ->where('floor_id', $this->sewingFloorId)
            ->whereDate('production_date', $this->sewingDate)
            ->get();
    }

    public static function setTqmSewingCriteria($data): array
    {
        return [
            'factory_id' => $data['factory_id'],
            'floor_id' => $data['floor_id'],
            'line_id' => $data['line_id'],
            'buyer_id' => $data['buyer_id'],
            'order_id' => $data['order_id'],
            'purchase_order_id' => $data['purchase_order_id'],
        ];
    }
}
