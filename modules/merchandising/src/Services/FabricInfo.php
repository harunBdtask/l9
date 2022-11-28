<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

class FabricInfo
{
    public static function getUnit($id): string
    {
        if (!$id) {
            return '';
        }

        return  [
            '1' => 'Kg',
            '2' => 'Yards',
            '3' => 'Meter',
            '4' => 'Pcs'
        ][$id];
    }
}