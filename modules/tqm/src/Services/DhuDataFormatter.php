<?php

namespace SkylarkSoft\GoRMG\TQM\Services;


class DhuDataFormatter
{
    private $productionDate;
    private $type;
    private $data;
    private $existingDhu;
    private $factoryId;
    private $cuttingFloorId;
    private $sewingFloorId;
    private $finishingFloorId;

    public function __construct()
    {
        $this->factoryId = request('factory_id') ?? factoryId();
        $this->cuttingFloorId = request('cutting_floor_id') ?? null;
        $this->sewingFloorId = request('sewing_floor_id') ?? null;
        $this->finishingFloorId = request('finishing_floor_id') ?? null;
    }

    /**
     * @return mixed
     */
    public function getProductionDate()
    {
        return $this->productionDate;
    }

    /**
     * @param mixed $date
     */
    public function setProductionDate($date): DhuDataFormatter
    {
        $this->productionDate = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): DhuDataFormatter
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): DhuDataFormatter
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDhu()
    {
        return $this->existingDhu;
    }

    /**
     * @param mixed $dhu
     */
    public function setDhu($dhu): DhuDataFormatter
    {
        $this->existingDhu = $dhu;
        return $this;
    }

    public function format()
    {
        return $this->getData()->map(function ($item) {
            $existingDhu = $this->existingDhuLoopWise($item);
            $details = collect($existingDhu->details ?? [])->map(function ($item) {
                $item['defect_name'] = $item->tqmDefect->name ?? null;
                return $item;
            });
            return [
                'factory_id' => $this->factoryId,
                'production_date' => $this->getProductionDate(),
                'cutting_floor_id' => $this->cuttingFloorId,
                'cutting_table_id' => $item->cutting_table_id ?? null,
                'table_no' => $item->cuttingTable->table_no ?? '',
                'floor_id' => $this->sewingFloorId,
                'line_id' => $item->line_id ?? null,
                'line_no' => $item->line->line_no ?? null,
                'finishing_floor_id' => $this->finishingFloorId,
                'finishing_table_id' => $item->finishing_table_id ?? null,
                'finishing_table_no' => $item->table->name ?? null,
                'buyer_id' => $item->buyer_id ?? null,
                'order_id' => $item->order_id ?? null,
                'purchase_order_id' => $item->purchase_order_id ?? $item->po_id ?? null,
                'checked' => $existingDhu->checked ?? null,
                'qc_pass' => $existingDhu->qc_pass ?? null,
                'total_defect' => $existingDhu->total_defect ?? null,
                'reject' => $existingDhu->reject ?? null,
                'reason' => $existingDhu->reason ?? null,
                'dhu_level' => $existingDhu->dhu_level ?? null,
                'details' => $details,
                'calculation' => [],
                'buyer_name' => $item->buyer->name ?? '',
                'style_name' => $item->order->style_name ?? '',
                'po_no' => $item->purchaseOrder->po_no ?? '',
            ];
        });
    }

    private function existingDhuLoopWise($item)
    {
        $poId = $item->purchase_order_id ?? $item->po_id ?? null;
        $existingDhu = $this->getDhu()->where('factory_id', $this->factoryId)
            ->where('buyer_id', $item->buyer_id)
            ->where('order_id', $item->order_id)
            ->where('purchase_order_id', $poId)
            ->where('production_date', $this->getProductionDate());

        if ($this->getType() == 'cutting') {
            $existingDhu = $existingDhu->where('cutting_floor_id', $this->cuttingFloorId)
                ->where('cutting_table_id', $item->cutting_table_id);
        }

        if ($this->getType() == 'sewing') {
            $existingDhu = $existingDhu->where('floor_id', $this->sewingFloorId)
                ->where('line_id', $item->line_id);
        }

        if ($this->getType() == 'finishing') {
            $existingDhu = $existingDhu->where('finishing_floor_id', $this->finishingFloorId)
                ->where('finishing_table_id', $item->finishing_table_id);
        }

        return $existingDhu->first();
    }
}
