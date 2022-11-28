<?php

namespace SkylarkSoft\GoRMG\Merchandising\DTO;

class OrderReportDTO
{
    private $factoryId;
    private $buyerId;
    private $jobNo;
    private $uniqueId;
    private $poNo;
    private $fromDate;
    private $toDate;
    private $styleName;
    private $searchType;
    private $dealingMerchantId;

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
    public function setFactoryId($factoryId): void
    {
        $this->factoryId = $factoryId;
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
    public function setBuyerId($buyerId): void
    {
        $this->buyerId = $buyerId;
    }

    /**
     * @return mixed
     */
    public function getJobNo()
    {
        return $this->jobNo;
    }

    /**
     * @param mixed $jobNo
     */
    public function setJobNo($jobNo): void
    {
        $this->jobNo = $jobNo;
    }
        /**
     * @return mixed
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * @param mixed $jobNo
     */
    public function setUniqueId($uniqueId): void
    {
        $this->uniqueId = $uniqueId;
    }

    /**
     * @return mixed
     */
    public function getPoNo()
    {
        return $this->poNo;
    }

    /**
     * @param mixed $poNo
     */
    public function setPoNo($poNo): void
    {
        $this->poNo = $poNo;
    }

    /**
     * @return mixed
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @param mixed $fromDate
     */
    public function setFromDate($fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return mixed
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @param mixed $toDate
     */
    public function setToDate($toDate): void
    {
        $this->toDate = $toDate;
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
    public function setStyleName($styleName): void
    {
        $this->styleName = $styleName;
    }

    /**
     * @return mixed
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * @param mixed $searchType
     */
    public function setSearchType($searchType): void
    {
        $this->searchType = $searchType;
    }

    /**
     * @return mixed
     */
    public function getDealingMerchantId()
    {
        return $this->dealingMerchantId;
    }

    /**
     * @param mixed $dealingMerchantId
     */
    public function setDealingMerchantId($dealingMerchantId): void
    {
        $this->dealingMerchantId = $dealingMerchantId;
    }
}
