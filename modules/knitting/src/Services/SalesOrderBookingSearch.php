<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class SalesOrderBookingSearch
{
    const SEARCH_AS_DEFAULT_SELECT = [0,-1];

    private $searchType;
    private $buyerId;
    private $factoryId;
    private $unitId;
    private $bookingStartDate;
    private $bookingEndDate;
    private $bookingNo;
    private $isProduction;
    private $umoId;
    private $styleName;
    private $orderNo;
    private $process;
    private $colorRange;


    private $bindings = [
        'main' => MainFabricBookingSearch::class,
        'short' => ShortFabricBookingSearch::class,
        'confirm_order' => ConfirmOrderFabricBookingSearch::class,
        'before_order' => BeforeOrderFabricBookingSearch::class,
        'sample' => SampleSearch::class,
    ];

    private function __construct($searchType)
    {
        $this->searchType = $searchType;
    }

    public static function for($searchType): SalesOrderBookingSearch
    {
        return new static($searchType);
    }

    public function getSearchType()
    {
        return $this->searchType;
    }


    /**
     * @return mixed
     */
    public function getBuyerId()
    {
        return $this->buyerId;
    }

    /**
     * @param mixed $buyerId
     */
    public function setBuyerId($buyerId): SalesOrderBookingSearch
    {
        $this->buyerId = $buyerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFactoryId()
    {
        return $this->factoryId;
    }

    /**
     * @param mixed $factoryId
     */
    public function setFactoryId($factoryId): SalesOrderBookingSearch
    {
        $this->factoryId = $factoryId;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getUnitId()
    {
        return $this->unitId;
    }

    /**
     * @param mixed $unitId
     */
    public function setUnitId($unitId): SalesOrderBookingSearch
    {
        $this->unitId = $unitId;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param mixed $process
     */
    public function setProcessId($process): SalesOrderBookingSearch
    {
        $this->process = $process;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBookingStartDate()
    {
        return $this->bookingStartDate;
    }

    /**
     * @param mixed $bookingStartDate
     */
    public function setBookingStartDate($bookingStartDate): SalesOrderBookingSearch
    {
        $this->bookingStartDate = $bookingStartDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBookingEndDate()
    {
        return $this->bookingEndDate;
    }

    /**
     * @param mixed $bookingEndDate
     */
    public function setBookingEndDate($bookingEndDate): SalesOrderBookingSearch
    {
        $this->bookingEndDate = $bookingEndDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBookingNo()
    {
        return $this->bookingNo;
    }

    /**
     * @param mixed $bookingNo
     */
    public function setBookingNo($bookingNo): SalesOrderBookingSearch
    {
        $this->bookingNo = $bookingNo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsProduction()
    {
        return $this->isProduction;
    }

    /**
     * @param mixed $isProduction
     */
    public function setIsProduction($isProduction): SalesOrderBookingSearch
    {
        $this->isProduction = $isProduction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUMOId()
    {
        return $this->umoId;
    }

    /**
     * @param mixed $umoId
     */
    public function setUMOId($umoId): SalesOrderBookingSearch
    {
        $this->umoId = $umoId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStyleName()
    {
        return $this->styleName;
    }

    /**
     * @param mixed $styleName
     */
    public function setStyleName($styleName): SalesOrderBookingSearch
    {
        $this->styleName = $styleName;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @param mixed $orderNo
     */
    public function setOrderNo($orderNo): SalesOrderBookingSearch
    {
        $this->orderNo = $orderNo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColorRange()
    {
        return $this->orderNo;
    }

    /**
     * @param mixed $colorRange
     */
    public function setColorRange($colorRange): SalesOrderBookingSearch
    {
        $this->colorRange = $colorRange;
        return $this;
    }

    public function mappedMainShortFabricBreakdown($fabricCollection, $salesOrderBookingSearch): array
    {
        $breakdown = collect($fabricCollection->detailsBreakdown)
            ->map(function ($detailsCollection) {
                $fabricBudgetDetails = $detailsCollection->budget->fabricCosting->details['details']['fabricForm'] ?? [];
                $yarnCostForm = $detailsCollection->budget->fabricCosting->details['details']['yarnCostForm'] ?? [];
                $fabricDescription = $detailsCollection->construction . " [" . $detailsCollection->composition . "]";

                $colorType = $detailsCollection->color_type_id <= 0 ? self::SEARCH_AS_DEFAULT_SELECT : [$detailsCollection->color_type_id];
                $fabricBudget = collect($fabricBudgetDetails)
                    ->where('body_part_id', $detailsCollection->body_part_id)
                    ->whereIn('color_type_id',$colorType)
                    ->where('fabric_composition_value', $fabricDescription)
                    ->first();

                return [
                    'gmt_color_id' => null,
                    'item_color_id' => $detailsCollection->color_id,
                    'color_range_id' => $this->colorRange->id ?? '',
                    'color_range' => $this->colorRange->name ?? '',
                    'fabric_nature' => $fabricBudget['fabric_nature_value'] ?? null,
                    'order_no' => $detailsCollection->po_no,
                    'garments_item_id' => $fabricBudget['garment_item_id'] ?? null,
                    'breakdown_id' => $detailsCollection->id,
                    'body_part' => $detailsCollection->body_part_value,//new
                    'body_part_id' => $detailsCollection->body_part_id,
                    'color_type_id' => $detailsCollection->color_type_id,
                    'color_type' => $detailsCollection->color_type_value,
                    'fabric_composition_id' => $fabricBudget['fabric_composition_id'] ?? null,
                    'fabric_description' => $fabricDescription,
                    'fabric_gsm' => $detailsCollection->gsm,
                    'fabric_dia' => $detailsCollection->dia ?? 0,
                    'fabric_nature_id' => $fabricBudget['fabric_nature_id'] ?? null,
                    'fabric_nature_value' => $fabricBudget['fabric_nature_value'] ?? null,
                    'dia_type_id' => $detailsCollection->dia_type,
                    'dia_type' => $detailsCollection->dia_type_value,
                    'color' => $detailsCollection->color_id,
                    'gmt_color' => $detailsCollection->gmt_color,
                    'item_color' => $detailsCollection->item_color,
                    'cons_uom' => $detailsCollection->uom,
                    'booking_qty' => $detailsCollection->actual_wo_qty,
                    'average_price' => $detailsCollection->rate,
                    'amount' => $detailsCollection->amount,
                    'prog_uom' => $this->getUMOId(),
                    'finish_qty' => $detailsCollection->actual_wo_qty,
                    'process_loss' => 0,
                    'gray_qty' => $detailsCollection->actual_wo_qty,
                    'process_id' => $this->process->id ?? null,
                    'process' => $this->process->process_name ?? null,
                    'remarks' => null,
                    'ld_no' => $detailsCollection->remarks,
                    'yarnInfo' => $yarnCostForm,
                ];
            });
        $job_no = $fabricCollection->detailsBreakdown->first()->job_no ?? null;
        if ($job_no) {
            $orderQuery = Order::query()->with([
                'season',
                'teamLeader',
                'dealingMerchant',
            ])->where('job_no', $job_no)->first();
            $shipMode = $orderQuery->ship_mode ?? null;
            $season_id = $orderQuery->season_id ?? null;
            $season_name = $orderQuery->season->season_name ?? null;
            $teamLeader = $orderQuery->teamLeader->screen_name ?? null;
            $dealingMerchant = $orderQuery->dealingMerchant->screen_name ?? null;
        }
        return [

            'ship_mode' => $shipMode ?? null,
            'season_id' => $season_id ?? null,
            'team_leader' => $teamLeader ?? null,
            'season_name' => $season_name ?? null,
            'dealing_merchant' => $dealingMerchant ?? null,
            'unit_id' => $fabricCollection->factory_id,
            'booking_type' => request('search_by') ?? null,
            'unapproved_request' => $fabricCollection->unapproved_request,

            'booking_id' => $fabricCollection->id,
            'style_name' => $fabricCollection->style_name,
            'supplier_id' => $fabricCollection->supplier_id,
            'supplier_value' => optional($fabricCollection->supplier)->name,
            'buyer_id' => optional($fabricCollection->buyer)->id,
            'buyer_value' => optional($fabricCollection->buyer)->name,
            'budget_unique_id' => $fabricCollection->detailsBreakdown->first()->job_no ?? '',
            'job_no' => $fabricCollection->detailsBreakdown->first()->job_no ?? '',
            'booking_no' => $fabricCollection->unique_id,
            'booking_date' => $fabricCollection->booking_date,
            'delivery_date' => $fabricCollection->delivery_date,
            'currency_id' => $fabricCollection->currency_id,
            'currency_value' => optional($fabricCollection->currency)->currency_name,
            'ready_to_approve' => $fabricCollection->ready_to_approve,
            'un_approve_request' => $fabricCollection->un_approve_request,
            'fabric_composition' => $fabricCollection->fabric_composition,
            'attention' => $fabricCollection->attention,
            'is_approve' => $fabricCollection->is_approve,
            'breakdown' => $breakdown
        ];
    }

    public function mappedBeforeAfterBookingBreakdown($fabricCollection, $salesOrderBookingSearch): array
    {
        $breakdown = collect($fabricCollection->details)
            ->map(function ($detailsCollection) use ($salesOrderBookingSearch) {
                $programUOM = null;
                $finishQty = $detailsCollection->total_qty;
                if ($detailsCollection->uom == $salesOrderBookingSearch->getUMOId()) {
                    $programUOM = $detailsCollection->uom_id;
                }
                return [
                    'garments_item_id' => $detailsCollection->gmts_item_id,
                    'breakdown_id' => $detailsCollection->id,
                    'body_part_id' => $detailsCollection->body_part_id,
                    'color_type_id' => $detailsCollection->color_type_id,
                    'fabric_description' => null,
                    'fabric_composition_id' => null,
                    'fabric_gsm' => $detailsCollection->gsm,
                    'fabric_dia' => $detailsCollection->dia ?? 0,
                    'dia_type_id' => $detailsCollection->dia_type,
                    'color' => $detailsCollection->color_id,
                    'color_range' => $this->colorRange->name,
                    'color_range_id' => $this->colorRange->id,
                    'cons_uom' => $detailsCollection->uom,
                    'booking_qty' => $detailsCollection->total_qty,
                    'average_price' => $detailsCollection->rate,
                    'amount' => $detailsCollection->amount,
                    'prog_uom' => $programUOM,
                    'finish_qty' => $finishQty,
                    'process_loss' => null,
                    'gray_qty' => $finishQty,
                    'process_id' => $this->process->id ?? null,
                    'process' => $this->process->process_name ?? null,
                    'remarks' => null,
                ];
            });

        return [
            'booking_id' => $fabricCollection->id,
            'supplier_id' => $fabricCollection->supplier_id,
            'supplier_value' => optional($fabricCollection->supplier)->name,
            'buyer_id' => optional($fabricCollection->buyer)->id,
            'buyer_value' => optional($fabricCollection->buyer)->name,
            'style_name' => $fabricCollection->style_name,
            'booking_no' => $fabricCollection->booking_no,
            'booking_date' => $fabricCollection->booking_date,
            'delivery_date' => $fabricCollection->delivery_date,
            'currency_id' => $fabricCollection->currency_id,
            'currency_value' => optional($fabricCollection->currency)->currency_name,
            'ready_to_approve' => $fabricCollection->ready_to_approve,
            'un_approve_request' => null, //$fabricCollection->un_approve_request,
            'fabric_composition' => null, //$fabricCollection->fabric_composition,
            'attention' => $fabricCollection->attention,
            'is_approve' => null, //$fabricCollection->is_approve,
            'breakdown' => $breakdown
        ];
    }

    public function mappedSampleBreakdown($fabricCollection, $salesOrderBookingSearch)
    {
        $sampleDetails = $fabricCollection->details()->get();
        $fabricMain = $fabricCollection->fabrics()->first();
        $fabricDetails = $fabricCollection->fabricDetails()->first();

        $processData = collect($fabricCollection->fabricDetails)->map(function($value, $key)use($fabricMain, $fabricCollection, $sampleDetails){
            $diaType = [];
            if ($value->details['dia_type']) {
                $diaType = collect($fabricCollection->dia_types)->where('id', $value->details['dia_type'])->first();
            }

            return [
                'gmt_color_id' => $value->details['gmts_color_id'],
                'item_color_id' => $value->details['gmts_color_id'],
                'color_range_id' => null,
                'color_range' => null,
                'fabric_nature' => $fabricMain->fabricNature->name ?? null,
                'order_no' => null,
                'garments_item_id' => $sampleDetails[$key]->gmts_item_id ?? null,
                'breakdown_id' => $value->id,
                'body_part' => $value->bodyPart->name ?? null,
                'body_part_id' => $value->body_part_id ?? null,
                'color_type_id' => $value->details['color_type_id'] ?? null,
                'color_type' => $value->colorType->color_types ?? null,
                'fabric_composition_id' => $value->details['fabric_description'] ?? null,
                'fabric_description' => $value->details['fabric_description_value'] ?? null,
                'fabric_gsm' => $value->calculations['gsm'] ?? null,
                'fabric_dia' => $value->calculations['finish_dia'] ?? null,
                'fabric_nature_id' => $fabricMain->fabric_nature_id ?? null,
                'fabric_nature_value' => $fabricMain->fabricNature->name ?? null,
                'dia_type_id' => $value->details['dia_type'],
                'dia_type' => $diaType['name'] ?? null,
                'color' => null,
                'gmt_color' => $value->color->name ?? null,
                'item_color' => $value->color->name ?? null,
                'cons_uom' => $value->details['uom_id'] ?? null,
                'booking_qty' => $value->calculations['total_req_qty'] ?? '0',
                'average_price' => $value->calculations['rate'] ?? '0',
                'amount' => $value->calculations['total_amount'] ?? '0',
                'prog_uom' => $this->getUMOId(),
                'finish_qty' => $value->calculations['total_req_qty'] ?? '0',
                'process_loss' => '0',
                'gray_qty' => $value->calculations['total_req_qty'] ?? '0',
                'process_id' => null,
                'process' => null,
                'remarks' => $value->details['remarks'],
                'ld_no' => null,
                'yarnInfo' => null,
            ];
        });


        $composition = $fabricDetails->details['fabric_description_value'] ?? null;

        return [

            'ship_mode' => null,
            'season_id' => null,
            'team_leader' => $fabricCollection->teamLeader->screen_name ?? null,
            'season_name' => null,
            'dealing_merchant' => $fabricCollection->dealingMerchant->screen_name ?? null,
            'unit_id' => $fabricCollection->factory_id ?? null,
            'booking_type' => request('search_by') ?? null,
            'unapproved_request' => null,

            'booking_id' => $fabricCollection->id,
            'style_name' => $fabricCollection->style_name,
            'supplier_id' => $fabricMain->supplier_id,
            'supplier_value' => $fabricMain->supplier->name ?? null,
            'buyer_id' => optional($fabricCollection->buyer)->id,
            'buyer_value' => optional($fabricCollection->buyer)->name,
            'budget_unique_id' => null,
            'job_no' => null,
            'booking_no' => $fabricCollection->requisition_id,
            'booking_date' => $fabricCollection->req_date,
            'delivery_date' => $fabricCollection->delivery_date,
            'currency_id' => $fabricCollection->currency ?? null,
            'currency_value' => optional($fabricCollection->currencyName)->currency_name,
            'ready_to_approve' => null,
            'un_approve_request' => null,
            'fabric_composition' => $composition ?? null,
            'attention' => null,
            'is_approve' => null,
            'breakdown' => $processData
        ];
    }

    public function response()
    {
        if (!isset($this->bindings[$this->getSearchType()])) {
            return null;
        }
        return (new $this->bindings[$this->getSearchType()])->format($this);
    }
}
