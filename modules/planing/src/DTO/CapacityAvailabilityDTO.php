<?php

namespace SkylarkSoft\GoRMG\Planing\DTO;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Planing\Models\FactoryCapacity;
use SkylarkSoft\GoRMG\Planing\Models\Settings\ItemCategory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class CapacityAvailabilityDTO
{
    private $dashboardData = [];
    private $categories = [];
    private $yearMonths = [];
    private $company;
    private $year;
    private $month;

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->month = $month;
    }

    /**
     * @return string
     */
    private function getYearMonth(): string
    {
        if (is_null($this->getMonth())) {
            return $this->getYear() . '-01';
        }

        return $this->getYear() . '-' . $this->getMonth();
    }

    /**
     * @return string
     */
    private function startDate(): string
    {
        if (is_null($this->getMonth())) {
            return Carbon::make($this->getYearMonth())->startOfYear()->format('Y-m-d');
        }

        return Carbon::make($this->getYearMonth())->startOfMonth()->format('Y-m-d');
    }

    /**
     * @return string
     */
    private function endDate(): string
    {
        if (is_null($this->getMonth())) {
            return Carbon::make($this->getYearMonth())->endOfYear()->format('Y-m-d');
        }

        return Carbon::make($this->getYearMonth())->endOfMonth()->format('Y-m-d');
    }

    /**
     * @return CarbonPeriod
     */
    private function generateMonthRange(): CarbonPeriod
    {
        return CarbonPeriod::create($this->startDate(), '1 month', $this->endDate());
    }

    /**
     * @return array
     */
    public function getDashboardData(): array
    {
        return $this->dashboardData;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return array
     */
    public function getYearMonths(): array
    {
        return $this->yearMonths;
    }

    /**
     * @return array
     */
    private function factoryCapacity(): array
    {
        return FactoryCapacity::query()
            ->with('itemCategory')
            ->select('capacity_pcs', 'item_category_id', 'date', 'capacity_available_mins', 'floor_id')
            ->whereBetween('date', [$this->startDate(), $this->endDate()])
            ->get()
            ->map(function ($item) {
                return [
                    'qty' => $item->capacity_pcs,
                    'capacity_available_mins' => $item->capacity_available_mins,
                    'item_id' => $item->item_category_id,
                    'item' => $item->itemCategory['name'],
                    'month' => Carbon::make($item->date)->format('M'),
                    'floor_id' => $item->floor_id,
                ];
            })->toArray();
    }

    private function itemWisePOData(): array
    {
        $poColorSizeBreakDown = PoColorSizeBreakdown::query()
            ->with([
                'garmentItem:id,name',
                'purchaseOrder:id,ex_factory_date,order_id',
                'purchaseOrder.order:id,smv',
            ])
            ->when($this->getCompany(), function ($query) {
                $query->where('factory_id', $this->getCompany());
            })
            ->selectRaw('garments_item_id,purchase_order_id, SUM(quantity) AS total_qty')
            ->whereHas('purchaseOrder', function ($q) {
                $q->whereBetween('ex_factory_date', [$this->startDate(), $this->endDate()]);
            })
            ->groupBy(['garments_item_id', 'purchase_order_id'])
            ->get()
            ->map(function ($item) {
                $item['qty'] = $item->total_qty;
                $item['item_id'] = $item->garments_item_id;
                $item['item_name'] = $item->garmentItem['name'];
                $item['shipment_month'] = Carbon::make($item->purchaseOrder->ex_factory_date)->format('M');
                $item['po_smv'] = $item->purchaseOrder->order->smv;
                $item['po_capacity_available_in_min'] = $item->total_qty * $item->purchaseOrder->order->smv;

                return $item;
            })->toArray();

        $itemCategories = ItemCategory::query()
            ->get()
            ->map(function ($collection) use ($poColorSizeBreakDown) {
                $categoryWisePoBreakdown = collect($poColorSizeBreakDown)
                    ->where('po_smv', '>=', $collection->smv_from)
                    ->where('po_smv', '<=', $collection->smv_to)
                    ->values()
                    ->toArray();

                return [
                    'category' => $collection->name,
                    'category_id' => $collection->id,
                    'smv_from' => $collection->smv_from,
                    'smv_to' => $collection->smv_to,
                    'category_wise_po_breakdown' => $categoryWisePoBreakdown,
                    'category_wise_po_breakdown_qty_sum' => collect($categoryWisePoBreakdown)->sum('qty'),
                    'po_capacity_available_in_min_sum' => collect($categoryWisePoBreakdown)
                        ->sum('po_capacity_available_in_min'),
                ];
            })->toArray();

        return $itemCategories;
    }

    /**
     * @return $this
     */
    public function generateCapacity(): self
    {
        $itemWisePOData = $this->itemWisePOData();
        $factoryCapacity = $this->factoryCapacity();

        foreach ($this->generateMonthRange() as $month) {
            $monthName = $month->format('M');

            $itemWisePOQty = collect($itemWisePOData)
//                ->where('shipment_month', $monthName)
                ->groupBy('category_id')
                ->toArray();

            $capacityPlaning = collect($factoryCapacity)
                ->where('month', $monthName)
                ->groupBy('item_id')
                ->toArray();

            foreach ($itemWisePOQty as $key => $item) {
                $poCapacityAvailable = collect($item)->sum('po_capacity_available_in_min_sum') ?? 0;
                $planningCapacity = isset($capacityPlaning[$key]) ? collect($capacityPlaning[$key])->sum('capacity_available_mins') : 0;
                $balance = $planningCapacity - $poCapacityAvailable;
                $exceedQty = 0;
                $notExceedQty = 0;

                if ($balance < 0) {
                    $exceedQty = abs($balance);
                    $this->dashboardData[$month->format('M-Y')]['po'][] = $planningCapacity;
                    $this->dashboardData[$month->format('M-Y')]['planning'][] = 0;
                } else {
                    $notExceedQty = $balance;
                    $this->dashboardData[$month->format('M-Y')]['planning'][] = $planningCapacity;
                }
                $this->dashboardData[$month->format('M-Y')]['exceed'][] = $exceedQty;
//                $this->dashboardData[$month->format('M-Y')]['balance'][] = $notExceedQty;
                $this->dashboardData[$month->format('M-Y')]['balance'][] = 0;
            }

            $this->categories[] = collect($itemWisePOQty)->collapse()->pluck('category')->unique()->values();
            $this->yearMonths[] = $month->format('M-Y');
        }


        return $this;
    }

    public function floorWiseCapacity()
    {
        return Floor::query()
            ->withSum(['capacities as total_capacity' => function (Builder $query) {
                return $query->whereMonth('date', $this->getMonth());
            }], 'capacity_available_mins')
            ->where('factory_id', $this->getCompany())
            ->get();
    }

    public function floorWithCategoryCapacity()
    {
        $factoryCapacity = $this->factoryCapacity();
        $categoryCapacity = $this->categoryWiseCapacity();

        return Floor::query()
            ->with('capacities.itemCategory')
            ->withSum(['capacities as total_capacity' => function (Builder $query) {
                return $query->whereMonth('date', $this->getMonth());
            }], 'capacity_available_mins')
            ->where('factory_id', $this->getCompany())
            ->get()
            ->map(function ($collection) use ($factoryCapacity, $categoryCapacity) {
                return [
                    'floor_no' => $collection->floor_no,
                    'total_capacity' => $collection->total_capacity,
                    'category_wise_capacity' => collect($categoryCapacity)->map(function ($categoryCollection) use ($collection, $factoryCapacity) {
                        $capacity = collect($factoryCapacity)
                            ->where('floor_id', $collection->id)
                            ->where('item_id', $categoryCollection->id)
                            ->sum('capacity_available_mins');

                        return [
                            'category_id' => $categoryCollection->id,
                            'category' => $categoryCollection->name,
                            'floor_id' => $collection->id,
                            'floor' => $collection->floor_no,
                            'capacity' => $capacity,
                        ];
                    }),
                ];
            });

    }

    public function categoryWiseCapacity()
    {
        return ItemCategory::query()
            ->withSum(['capacities as total_capacity' => function (Builder $query) {
                return $query->whereMonth('date', $this->getMonth());
            }], 'capacity_available_mins')
//            ->where('factory_id', $this->getCompany())
            ->get();
    }
}
