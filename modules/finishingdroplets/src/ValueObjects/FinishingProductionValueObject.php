<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\ValueObjects;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Poly;
use SkylarkSoft\GoRMG\Iedroplets\Models\Shipment;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;

class FinishingProductionValueObject
{
    private $from;
    private $to;
    private $buyerId;
    private $finishingFloor;
    private $sewingFloor;

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return mixed
     */
    private function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to): self
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return mixed
     */
    private function getBuyerId()
    {
        return $this->buyerId;
    }

    /**
     * @param mixed $buyerId
     */
    public function setBuyerId($buyerId): self
    {
        $this->buyerId = $buyerId;
        return $this;
    }

    /**
     * @return mixed
     */
    private function getFinishingFloor()
    {
        return $this->finishingFloor;
    }

    /**
     * @param mixed $finishingFloor
     */
    public function setFinishingFloor($finishingFloor): self
    {
        $this->finishingFloor = $finishingFloor;
        return $this;
    }

    /**
     * @return mixed
     */
    private function getSewingFloor()
    {
        return $this->sewingFloor;
    }

    /**
     * @param mixed $sewingFloor
     */
    public function setSewingFloor($sewingFloor): self
    {
        $this->sewingFloor = $sewingFloor;
        return $this;
    }

    private function getTodayDate(): string
    {
        return Carbon::now()->format('Y-m-d');
    }

    private function getPreviousDate(): string
    {
        return Carbon::now()->subDay()->format('Y-m-d');
    }

    private function getPolyProduction()
    {
        return Poly::query()
            ->with(['buyer', 'order', 'purchaseOrder.country', 'purchaseOrder.purchaseOrderDetails'])
            ->when($this->getBuyerId(), function ($query) {
                $query->where('buyer_id', $this->getBuyerId());
            })
            ->when($this->getFinishingFloor(), function ($query) {
                $query->where('finishing_floor_id', $this->getFinishingFloor());
            })
            ->whereDate('created_at', '>=', $this->getFrom())
            ->whereDate('created_at', '<=', $this->getTo())
            ->get();
    }

    private function getFinishingProductions($buyerIds, $orderIds, $purchaseOrderIds, $colorIds)
    {
        return FinishingProductionReport::query()
            ->with('floorWithoutGlobalScopes')
            ->whereIn('buyer_id', $buyerIds)
            ->whereIn('order_id', $orderIds)
            ->whereIn('purchase_order_id', $purchaseOrderIds)
            ->whereIn('color_id', $colorIds)
            ->when($this->getSewingFloor(), function ($query) {
                $query->where('floor_id', $this->getSewingFloor());
            })
            ->get();
    }

    private function getShipments($purchaseOrderIds)
    {
        return Shipment::query()
            ->where('purchase_order_id', $purchaseOrderIds)
            ->get();
    }

    private function format($polyProduction, $finishingProductionReport, $shipments): array
    {
        $orderQty = $polyProduction->purchaseOrder->purchaseOrderDetails
            ->where('color_id', $polyProduction->color_id)->sum('quantity') ?? 0;
        $orderQtyEx = $orderQty + ($orderQty / 100);
        $dailyReceived = $finishingProductionReport
            ->where('production_date', $this->getTodayDate())
            ->sum('sewing_output_qty');
        $prevReceived = $finishingProductionReport
            ->where('production_date', $this->getPreviousDate())
            ->sum('sewing_output_qty');
        $dailyPoly = $polyProduction->where('production_date', $this->getTodayDate());
        $prevPoly = $polyProduction->where('production_date', $this->getPreviousDate());

        return [
            'buyer' => $polyProduction->buyer->name,
            'style_name' => $polyProduction->order->style_name,
            'po_no' => $polyProduction->purchaseOrder->po_no,
            'country' => $polyProduction->purchaseOrder->country->name,
            'color' => $polyProduction->color->name,
            'order_qty' => $orderQty,
            'order_qty_ex' => $orderQtyEx,
            'daily_received' => $dailyReceived,
            'prev_received' => $prevReceived,
            'total_received' => $finishingProductionReport->sum('sewing_output_qty'),
            'daily_iron' => $dailyPoly->sum('iron_qty'),
            'prev_iron' => $prevPoly->sum('iron_qty'),
            'total_iron' => $polyProduction->sum('iron_qty'),
            'daily_finish' => $dailyPoly->sum('poly_qty'),
            'prev_finish' => $prevPoly->sum('poly_qty'),
            'total_finish' => $polyProduction->sum('poly_qty'),
            'balance_qty' => $orderQty - $polyProduction->sum('poly_qty'),
            'ship_qty' => $shipments
                ->where('purchase_order_id', $polyProduction->purchase_order_id)
                ->sum('ship_quantity'),
            'finish_floor' => $polyProduction->finishingFloor->name,
            'sewing_floor' => $finishingProductionReport
                ->pluck('floorWithoutGlobalScopes.floor_no')
                ->unique()->implode(', '),
        ];
    }

    public function report(): array
    {
        $polyProductions = $this->getPolyProduction();
        if ($polyProductions->isEmpty()) {
            return [];
        }
        $buyerIds = $polyProductions->pluck('buyer_id');
        $orderIds = $polyProductions->pluck('order_id');
        $purchaseOrderIds = $polyProductions->pluck('purchase_order_id');
        $colorIds = $polyProductions->pluck('color_id');
        $finishingProductionReports = $this->getFinishingProductions($buyerIds, $orderIds, $purchaseOrderIds, $colorIds);
        $shipments = $this->getShipments($purchaseOrderIds);

        $reports = [];

        foreach ($polyProductions as $polyProduction) {
            $finishingProductionReport = $finishingProductionReports
                ->where('color_id', $polyProduction->color_id)
                ->where('purchase_order_id', $polyProduction->purchase_order_id);

            $reports[] = $this->format($polyProduction, $finishingProductionReport, $shipments);
        }

        return $reports;
    }
}
