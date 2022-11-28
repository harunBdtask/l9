<?php

namespace SkylarkSoft\GoRMG\TQM\Services;


use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\TQM\Models\TqmCuttingDhu;

class CuttingDhuDataService
{
    private $factoryId, $cuttingDate, $cuttingFloorId;

    public function __construct($request)
    {
        $this->factoryId = $request->get('factory_id') ?? factoryId();
        $this->cuttingDate = $request->get('cutting_date');
        $this->cuttingFloorId = $request->get('cutting_floor_id');
    }

    public function getData()
    {
        $bundleCards = BundleCard::query()
            ->select('cutting_table_id', 'buyer_id', 'order_id', 'purchase_order_id')
            ->with(['buyer:id,name', 'order:id,style_name', 'purchaseOrder:id,po_no', 'cuttingTable:id,table_no'])
            ->where('factory_id', $this->factoryId)
            ->where('cutting_date', $this->cuttingDate)
            ->where('cutting_floor_id', $this->cuttingFloorId)
            ->groupBy('cutting_table_id', 'buyer_id', 'order_id', 'purchase_order_id')
            ->get();

        $arraysForQuery = [
            'cuttingTableIdsArr' => $bundleCards->pluck('cutting_table_id')->unique()->values(),
            'buyerIdsArr' => $bundleCards->pluck('buyer_id')->unique()->values(),
            'orderIdsArr' => $bundleCards->pluck('order_id')->unique()->values(),
            'purchaseOrderIdsArr' => $bundleCards->pluck('purchase_order_id')->unique()->values(),
        ];

        $existingCuttingDhu = $this->existingCuttingDhus($arraysForQuery);

        return (new DhuDataFormatter())
            ->setData($bundleCards)
            ->setDhu($existingCuttingDhu)
            ->setProductionDate($this->cuttingDate)
            ->setType('cutting')
            ->format();
    }

    private function existingCuttingDhus($data)
    {
        return TqmCuttingDhu::query()
            ->with('details.tqmDefect')
            ->whereIn('cutting_table_id', $data['cuttingTableIdsArr'])
            ->whereIn('buyer_id', $data['buyerIdsArr'])
            ->whereIn('order_id', $data['orderIdsArr'])
            ->whereIn('purchase_order_id', $data['purchaseOrderIdsArr'])
            ->where('factory_id', $this->factoryId)
            ->where('cutting_floor_id', $this->cuttingFloorId)
            ->whereDate('production_date', $this->cuttingDate)
            ->get();
    }

    public static function setTqmCuttingCriteria($bundleCard): array
    {
        return [
            'factory_id' => $bundleCard['factory_id'],
            'cutting_floor_id' => $bundleCard['cutting_floor_id'],
            'cutting_table_id' => $bundleCard['cutting_table_id'],
            'buyer_id' => $bundleCard['buyer_id'],
            'order_id' => $bundleCard['order_id'],
            'purchase_order_id' => $bundleCard['purchase_order_id'],
        ];
    }
}
