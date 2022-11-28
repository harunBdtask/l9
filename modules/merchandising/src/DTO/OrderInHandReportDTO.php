<?php

namespace SkylarkSoft\GoRMG\Merchandising\DTO;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class OrderInHandReportDTO
{
    private $from_date;
    private $to_date;
    private $buyer_id;
    private $status;
    private $report;

    public function setFromDate($date): OrderInHandReportDTO
    {
        $this->from_date = $date;
        return $this;
    }

    public function getFromDate()
    {
        return $this->from_date;
    }

    public function setToDate($date): OrderInHandReportDTO
    {
        $this->to_date = $date;
        return $this;
    }

    public function getToDate()
    {
        return $this->to_date;
    }

    public function setBuyer($buyer_id): OrderInHandReportDTO
    {
        $this->buyer_id = $buyer_id;
        return $this;
    }

    public function getBuyer()
    {
        return $this->buyer_id;
    }

    public function setStatus($status): OrderInHandReportDTO
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function generateReport(): OrderInHandReportDTO
    {
        $this->report = PurchaseOrder::query()
            ->with([
                'buyer',
                'order.uom',
                'order.dealingMerchant',
                'order.factoryMerchant',
                'factory',
                'poDetails.garmentItem',
            ])
            ->whereDate('ex_factory_date', '>=', $this->from_date)
            ->whereDate('ex_factory_date', '<=', $this->to_date)
            ->when($this->buyer_id, function ($query) {
                $query->where('buyer_id', $this->buyer_id);
            })
            ->when($this->status && $this->status == '2', Filter::applyFilter('is_approved', '0'))
            ->when($this->status && $this->status == '1', Filter::applyFilter('is_approved', '1'))
            ->orderBy('order_id', 'ASC')
            ->get();
        return $this;
    }

    public function format()
    {
        return $this->report->map(function ($collection) {

            $exFactoryDateCarbon = Carbon::make($collection->ex_factory_date);
            $exFactoryDate = $collection->ex_factory_date ? $exFactoryDateCarbon->format('d/m/Y') : "";
            $poQty = $collection->poDetails->sum('quantity');
            $items = $collection->poDetails->pluck('garmentItem.name')->implode(', ') ?? null;

            return [
                'buyer' => $collection->buyer->name ?? null,
                'order_no' => $collection->order->style_name ?? null,
                'pcd_date' => $collection->order->pcd_date ?? null,
                'order_ref_no' => $collection->order->job_no ?? null,
                'po_no' => $collection->po_no,
                'ex_factory_date' => $exFactoryDate,
                'ex_factory_date_month_year' => $exFactoryDateCarbon->format('Y-m'),
                'uom' => $collection->order->uom->unit_of_measurement ?? null,
                'garments_item' => $items,
                'merchandiser' => $collection->order->dealingMerchant->screen_name ?? null,
                'factory' => $collection->factory->factory_name ?? null,
                'unit_price' => $collection->avg_rate_pc_set,
                'quantity' => $poQty,
                'total_fob' => $poQty * (double)$collection->avg_rate_pc_set,
                'pcd_remarks' => $collection->order->pcd_remarks ?? null,
                'ie_remarks' => $collection->order->ie_remarks ?? null,
            ];
        })->groupBy('ex_factory_date_month_year');
    }

}
