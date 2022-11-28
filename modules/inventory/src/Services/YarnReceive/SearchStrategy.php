<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceive;

use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;

class SearchStrategy
{
    private static $bindings = [
        YarnReceive::PI_BASIS => PIBasisSearchService::class
    ];

    /**
     * @param $piBasis
     * @return ReceiveBasisSearchContract
     */
    public static function for($piBasis): ?ReceiveBasisSearchContract
    {
        return (new self::$bindings[$piBasis]) ?? null;
    }
}
