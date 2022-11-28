<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use App\Models\UIDModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrimsIssueReturn extends UIDModel
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'trims_issue_returns';

    protected $primaryKey = 'id';

    protected $fillable = [
        'uniq_id',
        'factory_id',
        'return_date',
        'store_id',
        'challan_no',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function getConfig(): array
    {
        return ['abbr' => "TIR"];
    }

    public function details(): HasMany
    {
        return $this->hasMany(TrimsIssueReturnDetail::class, 'trims_issue_return_id');
    }
}
