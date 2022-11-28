<?php

namespace SkylarkSoft\GoRMG\TQM\Services;


use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use SkylarkSoft\GoRMG\TQM\Models\TqmFinishingDhu;

class FinishingDhuDataService
{
    private $factoryId, $finishingDate, $finishingFloorId;

    public function __construct($request)
    {
        $this->factoryId = $request->get('factory_id') ?? factoryId();
        $this->finishingDate = $request->get('finishing_date');
        $this->finishingFloorId = $request->get('finishing_floor_id');
    }

    public function getData()
    {
        $finishingProductions = HourWiseFinishingProduction::query()
            ->select('finishing_table_id', 'buyer_id', 'order_id', 'po_id')
            ->with(['buyer:id,name', 'order:id,style_name', 'purchaseOrder:id,po_no', 'table:id,name'])
            ->where('factory_id', $this->factoryId)
            ->whereDate('production_date', $this->finishingDate)
            ->where('finishing_floor_id', $this->finishingFloorId)
            ->whereNotNull('finishing_table_id')
            ->groupBy('finishing_table_id', 'buyer_id', 'order_id', 'po_id')
            ->get();

        $arraysForQuery = [
            'finishingTableIdsArr' => $finishingProductions->pluck('finishing_table_id')->unique()->values(),
            'buyerIdsArr' => $finishingProductions->pluck('buyer_id')->unique()->values(),
            'orderIdsArr' => $finishingProductions->pluck('order_id')->unique()->values(),
            'purchaseOrderIdsArr' => $finishingProductions->pluck('po_id')->unique()->values(),
        ];

        $existingFinishingDhu = $this->existingFinishingDhu($arraysForQuery);

        return (new DhuDataFormatter())
            ->setData($finishingProductions)
            ->setDhu($existingFinishingDhu)
            ->setProductionDate($this->finishingDate)
            ->setType('finishing')
            ->format();
    }

    private function existingFinishingDhu($data)
    {
        return TqmFinishingDhu::query()
            ->with('details.tqmDefect')
            ->whereIn('finishing_table_id', $data['finishingTableIdsArr'])
            ->whereIn('buyer_id', $data['buyerIdsArr'])
            ->whereIn('order_id', $data['orderIdsArr'])
            ->whereIn('purchase_order_id', $data['purchaseOrderIdsArr'])
            ->where('factory_id', $this->factoryId)
            ->where('finishing_floor_id', $this->finishingFloorId)
            ->whereDate('production_date', $this->finishingDate)
            ->get();
    }

    public static function setTqmFinishingCriteria($data): array
    {
        return [
            'factory_id' => $data['factory_id'],
            'finishing_floor_id' => $data['finishing_floor_id'],
            'finishing_table_id' => $data['finishing_table_id'],
            'buyer_id' => $data['buyer_id'],
            'order_id' => $data['order_id'],
            'purchase_order_id' => $data['purchase_order_id'],
        ];
    }
}
