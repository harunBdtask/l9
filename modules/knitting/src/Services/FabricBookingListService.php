<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class FabricBookingListService
{
    private $q;
    private $perPage = 15;

    /**
     * @param $q
     */
    public function __construct($q)
    {
        $this->q = $q;
    }

    public function getData(): array
    {
        $mainFabricData = $this->mainFabricBooking();
        $shortFabricData = $this->shortFabricBooking();
        $sampleFabricsData = $this->sampleFabrics();

        $mainFabricDataFormat = $this->format($mainFabricData, 'Main');
        $shortFabricDataFormat = $this->format($shortFabricData, 'Short');
        $sampleFabricsDataFormat = $this->sampleFormat($sampleFabricsData, 'Sample');

        $q = $this->q??null;
        if (!empty($q) && $q['type'] == 'sample') {
            $data['data'] = collect($sampleFabricsDataFormat)
            ->sortByDesc('date')
            ->values()->all();
            $data['pagination'] = $sampleFabricsData;
        }else {
            $data['data'] = collect(array_merge($mainFabricDataFormat, $shortFabricDataFormat))
            ->sortByDesc('date')
            ->values()->all();

            if ($mainFabricData->total() > $shortFabricData->total()) {
                $data['pagination'] = $mainFabricData;
            } else {
                $data['pagination'] = $shortFabricData;
            }

        }

        return $data;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function mainFabricBooking(): LengthAwarePaginator
    {
        $q = $this->q??null;
        if (!empty($q) && $q['type'] == 'fabric') {
            $q = $q['q'];
        }else{
            $q = null;
        }
        return FabricBooking::query()
            ->with(['buyer', 'supplier', 'createdBy'])
            ->withCount('fabricSalesOrder')
            ->when($this->q, function ($query) use ($q) {
                $query->where('unique_id', 'like', '%' . $q . '%');
            })
            ->orWhere('booking_date', 'like', '%' . $q . '%')
            ->orWhere('delivery_date', 'like', '%' . $q . '%')
            ->orWhereHas('detailsBreakdown.budget', function ($query) use ($q) {
                return $query->where('style_name', 'like', '%' . $q . '%');
            })
            ->orWhereHas('buyer', function ($query) use ($q) {
                return $query->where('name', 'like', '%' . $q . '%');
            })
            ->orWhereHas('createdBy', function ($query) use ($q) {
                return $query->where('first_name', 'like', '%' . $q . '%')->orWhere('first_name', 'like', '%' . $q . '%');
            })
            ->orWhereHas('supplier', function ($query) use ($q) {
                return $query->where('name', 'like', '%' . $q . '%');
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function shortFabricBooking(): LengthAwarePaginator
    {
        $q = $this->q??null;
        if (!empty($q) && $q['type'] == 'fabric') {
            $q = $q['q'];
        }else{
            $q = null;
        }
        return ShortFabricBooking::query()
            ->with(['buyer', 'supplier', 'createdBy'])
            ->withCount('fabricSalesOrder')
            ->when($this->q, function ($query) use ($q) {
                $query->where('unique_id', 'like', '%' . $q . '%');
            })
            ->orWhere('booking_date', 'like', '%' . $q . '%')
            ->orWhere('delivery_date', 'like', '%' . $q . '%')
            ->orWhereHas('detailsBreakdown.budget', function ($query) use ($q) {
                return $query->where('style_name', 'like', '%' . $q . '%');
            })
            ->orWhereHas('buyer', function ($query) use ($q) {
                return $query->where('name', 'like', '%' . $q . '%');
            })
            ->orWhereHas('createdBy', function ($query) use ($q) {
                return $query->where('first_name', 'like', '%' . $q . '%')->orWhere('first_name', 'like', '%' . $q . '%');
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);
    }

    /**
     * @param $fabricCollection
     * @param $type
     * @return array
     */
    public function format($fabricCollection, $type): array
    {
        return $fabricCollection->getCollection()->transform(function ($fabricCollection) use ($type) {
            return [
                'booking_type' => $type,
                'id' => $fabricCollection->id,
                'date' => $fabricCollection->created_at,
                'booking_id' => $fabricCollection->id,
                'buyer_id' => optional($fabricCollection->buyer)->id,
                'buyer_value' => optional($fabricCollection->buyer)->name,
                'style_name' => $fabricCollection->style_name,
                'booking_no' => $fabricCollection->unique_id,
                'created_at' => date('d-M-Y', strtotime($fabricCollection->created_at)),
                'booking_date' => date('d-M-Y', strtotime($fabricCollection->booking_date)),
                'delivery_date' => date('d-M-Y', strtotime($fabricCollection->delivery_date)),
                'created_by' => $fabricCollection->createdBy->full_name ?? '',
                'supplier_value' => $fabricCollection->supplier->name,
                'fabric_sales_order_count' => $fabricCollection->fabric_sales_order_count,
            ];
        })->toArray();
    }

    public function sampleFabrics(): LengthAwarePaginator
    {
        $q = $this->q??null;
        $buyer = null;
        if (!empty($q) && $q['type'] == 'sample') {
            $q = $q['q'];
            $buyer =  Buyer::where('name', $q)->first()->id??null;
        }else{
            $q = null;
        }
        return SampleOrderRequisition::query()
            ->with([
                'factory:id,factory_name',
                'buyer:id,name',
                'dealingMerchant:id,screen_name',
                'fabrics',
            ])
            ->when($buyer, function($query) use ($buyer){
                $query->where('buyer_id', $buyer);
            })
            ->when($q && !$buyer, function ($query) use ($q) {
                $query->orWhere('requisition_id', $q);
                $query->orWhere('style_name', $q);
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);
    }

    public function sampleFormat($sampleCollection, $type): array
    {
        return $sampleCollection->getCollection()->transform(function ($sampleCollection) use ($type) {
            return [
                'booking_type' => $type,
                'id' => $sampleCollection->id,
                'date' => $sampleCollection->created_at,
                'booking_id' => $sampleCollection->id,
                'buyer_id' => optional($sampleCollection->buyer)->id,
                'buyer_value' => $sampleCollection->buyer->name ?? null,
                'style_name' => $sampleCollection->style_name,
                'booking_no' => $sampleCollection->requisition_id,
                'created_at' => date('d-M-Y', strtotime($sampleCollection->created_at)),
                'booking_date' => date('d-M-Y', strtotime($sampleCollection->req_date)),
                'delivery_date' => date('d-M-Y', strtotime($sampleCollection->delivery_date)),
                'created_by' => $sampleCollection->dealingMerchant->screen_name ?? null,
                'supplier_value' => collect($sampleCollection->fabrics)->first()->supplier->name ?? null,
                'fabric_sales_order_count' => $sampleCollection->fabric_sales_order_count,
            ];
        })->toArray();
    }
}
