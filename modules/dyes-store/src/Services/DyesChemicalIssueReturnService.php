<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

use SkylarkSoft\GoRMG\DyesStore\Models\DsChemicalIssueReturn;

class DyesChemicalIssueReturnService
{
    public static function generateUniqueId(): string
    {
        $prefix = DsChemicalIssueReturn::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'DCIR-' . date('y') . '-' . $generate;
    }
}
