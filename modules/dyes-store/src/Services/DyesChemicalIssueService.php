<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsIssue;

class DyesChemicalIssueService
{
    public static function generateUniqueId(): string
    {
        $prefix = DyesChemicalsIssue::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'DCI-' . date('y') . '-' . $generate;
    }
}
