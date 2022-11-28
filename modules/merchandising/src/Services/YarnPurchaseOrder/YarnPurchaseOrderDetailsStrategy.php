<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Http\Request;

class YarnPurchaseOrderDetailsStrategy
{
    private const REQUISITION_BASIS = 1;
    private const STYLE_BASIS = 2;
    private $detailsType;
    private $implementors = [
        self::REQUISITION_BASIS => RequisitionBasisDetails::class,
        self::STYLE_BASIS => StyleBasisDetails::class,
    ];

    public function setStrategy($detailsType): YarnPurchaseOrderDetailsStrategy
    {
        $this->detailsType = $detailsType;
        return $this;
    }

    public function search(Request $request)
    {
        if (!isset($this->implementors[$this->detailsType])) {
            return false;
        }
        return (new $this->implementors[$this->detailsType])->search($request);
    }

}
