<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class YarnGatePassChallanScan extends Model
{
    protected $table = "yarn_gate_pass_challan_scan";
    protected $primaryKey = "id";
    protected $fillable = [
        'yarn_issue_id',
        'issue_no',
        'challan_no',
        'challan_date',
        'supplier_id',
        'gate_pass_no',
        'vehicle_number',
        'lock_no',
        'driver_name'
    ];


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function yarnIssue(): BelongsTo
    {
        return $this->belongsTo(YarnIssue::class,'yarn_issue_id');
    }

}
