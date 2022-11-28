<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class TermAndCondition extends Model
{
    // Types
    const GENERAL = 1;
    const SPECIAL = 2;

    // category
    const WORK_ORDER = 1;
    const PROFORMA_INVOICE = 2;

    protected $guarded = [];

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
