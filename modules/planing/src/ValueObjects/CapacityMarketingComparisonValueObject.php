<?php

namespace SkylarkSoft\GoRMG\Planing\ValueObjects;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Planing\Models\FactoryCapacity;
use SkylarkSoft\GoRMG\Planing\Models\Settings\ItemCategory;

class CapacityMarketingComparisonValueObject
{
    private $startDate;
    private $endDate;

    public function setStartDate($date)
    {
        $this->startDate = Carbon::make($date)->startOfMonth()->format('Y-m-d');
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setEndDate($date)
    {
        $this->endDate = Carbon::make($date)->endOfMonth()->format('Y-m-d');;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    private function factoryCapacity(): array
    {
        return FactoryCapacity::query()
            ->with('itemCategory')
            ->select('capacity_pcs', 'item_category_id', 'date', 'capacity_available_mins')
            ->whereBetween('date', [$this->getStartDate(), $this->getEndDate()])
            ->get()
            ->map(function ($item) {
                return [
                    'qty' => $item->capacity_pcs,
                    'capacity_available_mins' => $item->capacity_available_mins,
                    'item_id' => $item->item_category_id,
                    'item' => $item->itemCategory['name'],
                    'month' => Carbon::make($item->date)->format('M'),
                ];
            })->toArray();
    }

    private function poItemColorSizeDetails(): array
    {
        return PoColorSizeBreakdown::query()
            ->with([
                'garmentItem:id,name',
                'purchaseOrder:id,ex_factory_date,order_id,buyer_id',
                'purchaseOrder.order:id,smv,buyer_id',
                'purchaseOrder.order.buyer:id,name',
            ])
            ->selectRaw('garments_item_id,purchase_order_id, SUM(quantity) AS total_qty')
            ->whereHas('purchaseOrder', function ($q) {
                $q->whereBetween('ex_factory_date', [$this->getStartDate(), $this->getEndDate()]);
            })
            ->groupBy(['garments_item_id', 'purchase_order_id'])
            ->get()
            ->map(function ($item) {
                $itemSmv = $item->purchaseOrder->order['smv'];
                $item['qty'] = $item->total_qty;
                $item['item_id'] = $item->garments_item_id;
                $item['item_name'] = $item->garmentItem['name'];
                $item['shipment_month'] = Carbon::make($item->purchaseOrder->ex_factory_date)->format('M');
                $item['po_smv'] = $itemSmv;
                $item['buyer'] = $item->purchaseOrder->order->buyer->name;
                $item['buyer_id'] = $item->purchaseOrder->order->buyer->id;
                $item['po_capacity_available_in_min'] = $item->total_qty * $item->purchaseOrder->order->smv;

                return $item;
            })
            ->toArray();
    }

    public function report(): array
    {
        $poColorSizeBreakDown = $this->poItemColorSizeDetails();
        $factoryCapacity = $this->factoryCapacity();

        return ItemCategory::query()
            ->get()
            ->map(function ($collection) use ($poColorSizeBreakDown, $factoryCapacity) {
                $categoryWisePoBreakdown = collect($poColorSizeBreakDown)
                    ->where('po_smv', '>=', $collection->smv_from)
                    ->where('po_smv', '<=', $collection->smv_to)
                    ->values()
                    ->toArray();

                $availableCapacityMin = collect($factoryCapacity)
                    ->where('item_id', $collection->id)
                    ->sum('capacity_available_mins');


                $buyerWiseGroup = collect($categoryWisePoBreakdown)
                    ->groupBy('buyer_id')
                    ->values()
                    ->map(function ($collection) {
                        return [
                            'buyer' => $collection->first()['buyer'],
                            'total_qty' => $collection->sum('total_qty'),
                            'total_po_capacity_available_in_min' => $collection->sum('po_capacity_available_in_min'),
                        ];
                    })
                    ->toArray();

                $poTotalRequiredMin = collect($buyerWiseGroup)
                    ->sum('total_po_capacity_available_in_min');

                return [
                    'category' => $collection->name,
                    'category_id' => $collection->id,
                    'smv_from' => $collection->smv_from,
                    'smv_to' => $collection->smv_to,
                    'category_wise_po_breakdown' => $buyerWiseGroup,
                    'category_wise_po_breakdown_qty_sum' => collect($buyerWiseGroup)->sum('total_qty'),
                    'po_capacity_available_in_min_sum' => $poTotalRequiredMin,
                    'available_min' => $availableCapacityMin,
                    'balance_min' => $availableCapacityMin - $poTotalRequiredMin,
                ];
            })->toArray();
    }
}
