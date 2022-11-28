<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class AllocationBookingDataFormatter
{
    private $colorRangeId;
    private $colorRange;
    private $consUom;
    private $amount;
    private $garmentsItemId;
    private $yarnAllocationId;
    private $bodyPartId;
    private $colorTypeId;
    private $fabricDescription;
    private $fabricGsm;
    private $fabricDia;
    private $fabricNatureId;
    private $fabricNatureValue;
    private $diaTypeId;
    private $gmtColor;
    private $gmtColorId;
    private $itemColor;
    private $itemColorId;
    private $bookingQty;
    private $averagePrice;
    private $finishQty;
    private $processLoss;
    private $grayQty;
    private $diaType;

    public function __construct($data)
    {
        $this->setAmount($data['booking_qty'], $data['average_price']);
        $this->setColorRange($data['color_type_id']);
        $this->setColorRangeId($data['color_type_id']);
        $this->setConsUom();
        $this->garmentsItemId = $data['garments_item_id'];
        $this->yarnAllocationId = $data['yarn_allocation_id'];
        $this->bodyPartId = $data['body_part_id'];
        $this->colorTypeId = $data['color_type_id'];
        $this->fabricDescription = $data['fabric_description'];
        $this->fabricGsm = $data['fabric_gsm'];
        $this->fabricDia = $data['fabric_dia'];
        $this->fabricNatureId = $data['fabric_nature_id'];
        $this->fabricNatureValue = $data['fabric_nature_value'];
        $this->diaType = $data['dia_type'];
        $this->diaTypeId = $data['dia_type_id'];
        $this->gmtColor = $data['gmt_color'];
        $this->gmtColorId = $data['gmt_color_id'];
        $this->itemColor = $data['item_color'];
        $this->itemColorId = $data['item_color_id'];
        $this->bookingQty = $data['booking_qty'];
        $this->averagePrice = $data['average_price'];
        $this->finishQty = $data['finish_qty'];
        $this->processLoss = $data['process_loss'];
        $this->grayQty = $data['gray_qty'];
        $this->setGmtColorId();
        $this->setItemColorId();
    }

    /**
     * @return mixed
     */
    public function getColorRangeId()
    {
        return $this->colorRangeId;
    }

    /**
     * @param mixed $colorRangeId
     */
    public function setColorRangeId($colorRangeId): AllocationBookingDataFormatter
    {
        $this->colorRangeId = $colorRangeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColorRange()
    {
        return $this->colorRange;
    }

    /**
     * @param mixed $colorRangeId
     */
    private function setColorRange($colorRangeId): AllocationBookingDataFormatter
    {
        $this->colorRange = ColorType::query()->where('id', $colorRangeId)->first()->color_types ?? '';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsUom()
    {
        return $this->consUom;
    }

    public function setConsUom(): AllocationBookingDataFormatter
    {
        $query = UnitOfMeasurement::query()->where('unit_of_measurement', 'kg')->first();
        $this->consUom = $query ? $query->id : '';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $bookingQty, $avgPrice
     */
    public function setAmount($bookingQty, $avgPrice): AllocationBookingDataFormatter
    {
        $this->amount = $bookingQty * $avgPrice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGmtColorId()
    {
        return $this->gmtColorId;
    }

    /**
     * @return AllocationBookingDataFormatter
     */
    public function setGmtColorId(): AllocationBookingDataFormatter
    {
        $this->gmtColorId = Color::query()->where('name', $this->gmtColor)->first()->id ?? null;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemColorId()
    {
        return $this->itemColorId;
    }

    /**
     * @return AllocationBookingDataFormatter
     */
    public function setItemColorId(): AllocationBookingDataFormatter
    {
        $this->itemColorId = Color::query()->where('name', $this->itemColor)->first()->id ?? null;
        return $this;
    }


    public function format(): array
    {
        return [
            'color_range' => $this->colorRange,
            'color_range_id' => $this->colorRangeId,
            'cons_uom' => $this->consUom,
            'amount' => $this->amount,
            'prog_uom' => $this->consUom,
            'yarn_allocation_id' => $this->yarnAllocationId,
            'garments_item_id' => $this->garmentsItemId,
            'body_part_id' => $this->bodyPartId,
            'color_type_id' => $this->colorTypeId,
            'fabric_description' => $this->fabricDescription,
            'fabric_gsm' => $this->fabricGsm,
            'fabric_dia' => $this->fabricDia,
            'dia_type_id' => $this->diaTypeId,
            'dia_type' => $this->diaType,
            'gmt_color_id' => $this->gmtColorId,
            'gmt_color' => $this->gmtColor,
            'item_color_id' => $this->itemColorId,
            'item_color' => $this->itemColor,
            'booking_qty' => $this->bookingQty,
            'average_price' => $this->averagePrice,
            'finish_qty' => $this->finishQty,
            'process_loss' => $this->processLoss,
            'gray_qty' => $this->grayQty,
            'process_id' => null,
            'fabric_nature_id' => $this->fabricNatureId,
            'fabric_nature' => $this->fabricNatureValue,
        ];
    }
}

