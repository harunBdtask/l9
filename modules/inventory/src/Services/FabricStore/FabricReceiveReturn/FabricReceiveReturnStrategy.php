<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricReceiveReturn;

class FabricReceiveReturnStrategy
{
    const BARCODE = 'barcode', MANUAL = 'manual';

    protected $requestType, $request, $receiveReturnModel;

    protected $implementors = [
        self::BARCODE => FabricBarcodeReceiveReturn::class,
        self::MANUAL => FabricManualReceiveReturn::class,
    ];

    public function setStrategy($requestType): FabricReceiveReturnStrategy
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function setRequest($request): FabricReceiveReturnStrategy
    {
        $this->request = $request;

        return $this;
    }

    public function setReceiveReturnModel($receiveReturnModel): FabricReceiveReturnStrategy
    {
        $this->receiveReturnModel = $receiveReturnModel;

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

    public function getReceiveReturnModel()
    {
        return $this->receiveReturnModel;
    }

    public function getReceiveDetails()
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
