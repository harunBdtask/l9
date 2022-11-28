<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use App\Models\BelongsToSupplier;
use App\Models\UIDModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Stores;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class TrimsIssue extends UIDModel
{
    use SoftDeletes, ModelCommonTrait, BelongsToSupplier;

    protected $table = 'trims_issues';

    protected $fillable = [
        'uniq_id',
        'factory_id',
        'issue_basis',
        'issue_purpose',
        'issue_date',
        'issue_challan_no',
        'location',
        'store_id',
        'sewing_source',
        'sewing_composite',
        'sewing_location',
        'remarks',
        'floor_no',
    ];

    public static function getConfig(): array
    {
        return ['abbr' => "TI"];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Stores::class, 'store_id')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsIssueDetail::class, 'trims_issue_id');
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class,'floor_no')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class,'factory_id')->withDefault();
    }

}
