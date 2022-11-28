<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreFabricTransfer;

class MaterialFabricTransferService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubGreyStoreFabricTransfer::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SGST-' . date('y') . '-' . $generate;
    }
}
