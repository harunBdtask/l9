<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssueReturn;

class FabricIssueReturnStrategy
{
    const BARCODE = 'barcode', MANUAL = 'manual';

    protected $requestType, $request, $issueReturnModel;

    protected $implementors = [
        self::BARCODE => FabricBarcodeIssueReturn::class,
        self::MANUAL  => FabricManualIssueReturn::class,
    ];

    public function setStrategy($requestType): FabricIssueReturnStrategy
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function setRequest($request): FabricIssueReturnStrategy
    {
        $this->request = $request;

        return $this;
    }

    public function setIssueReturnModel($issueReturnModel): FabricIssueReturnStrategy
    {
        $this->issueReturnModel = $issueReturnModel;

        return $this;
    }

    public function getRequestType()
    {
        return $this->requestType;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getIssueReturnModel()
    {
        return $this->issueReturnModel;
    }

    public function getIssueDetails()
    {
        if (!isset($this->implementors[$this->requestType])) {
            return false;
        }

        return (new $this->implementors[$this->requestType])->handle($this);
    }

    public function storeDetail()
    {
        if (!isset($this->implementors[$this->requestType])) {
            return false;
        }

        return (new $this->implementors[$this->requestType])->store($this);
    }
}
