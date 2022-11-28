<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricTransfer;

class FabricTransferStrategy
{
    const BARCODE = 'barcode', MANUAL = 'manual';

    protected $requestType, $request, $transferModel;

    protected $implementors = [
        self::BARCODE => FabricBarcodeTransfer::class,
        self::MANUAL => FabricManualTransfer::class,
    ];

    public function setStrategy($requestType): FabricTransferStrategy
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function setRequest($request): FabricTransferStrategy
    {
        $this->request = $request;

        return $this;
    }

    public function setReceiveReturnModel($transferModel): FabricTransferStrategy
    {
        $this->transferModel = $transferModel;

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

    public function getTransferModel()
    {
        return $this->transferModel;
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
