<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\DTO;

class DailyChallanWiseInputDTO
{
    private $cuttingInventory;
    private $floorId;
    private $lineId;
    private $productionDate;
    private $dailyChallanInputModel;

    /**
     * @return mixed
     */
    public function getCuttingInventory()
    {
        return $this->cuttingInventory;
    }

    /**
     * @param mixed $cuttingInventory
     */
    public function setCuttingInventory($cuttingInventory): self
    {
        $this->cuttingInventory = $cuttingInventory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFloorId()
    {
        return $this->floorId;
    }

    /**
     * @param mixed $floorId
     */
    public function setFloorId($floorId): self
    {
        $this->floorId = $floorId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLineId()
    {
        return $this->lineId;
    }

    /**
     * @param mixed $lineId
     */
    public function setLineId($lineId): self
    {
        $this->lineId = $lineId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductionDate()
    {
        return $this->productionDate;
    }

    /**
     * @param mixed $productionDate
     */
    public function setProductionDate($productionDate): self
    {
        $this->productionDate = $productionDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDailyChallanInputModel()
    {
        return $this->dailyChallanInputModel;
    }

    /**
     * @param mixed $dailyChallanInputModel
     */
    public function setDailyChallanInputModel($dailyChallanInputModel): self
    {
        $this->dailyChallanInputModel = $dailyChallanInputModel;
        return $this;
    }
}
