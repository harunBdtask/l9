<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssue;

class FabricIssueStrategy
{
    const BARCODE = 'barcode', MANUAL = 'manual';

    protected $requestType, $request, $issueModel;

    protected $implementors = [
        self::BARCODE => FabricBarcodeIssue::class,
        self::MANUAL  => FabricManualIssue::class,
    ];

    public function setStrategy($requestType): FabricIssueStrategy
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function setRequest($request): FabricIssueStrategy
    {
        $this->request = $request;

        return $this;
    }

    public function setIssueModel($issueModel): FabricIssueStrategy
    {
        $this->issueModel = $issueModel;

        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getIssueModel()
    {
        return $this->issueModel;
    }

    public function getDetail()
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
