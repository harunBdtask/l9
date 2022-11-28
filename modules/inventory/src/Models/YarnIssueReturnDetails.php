<?php

namespace SkylarkSoft\GoRMG\Inventory\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class YarnIssueReturnDetails extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'yarn_issue_return_details';

    protected $fillable = [
        'yarn_issue_return_id',
        'yarn_count_id',
        'yarn_composition_id',
        'yarn_type_id',
        'yarn_color',
        'yarn_lot',
        'uom_id',
        'return_qty',
        'rate',
        'return_value',
        'store_id',
        'floor_id',
        'room_id',
        'rack_id',
        'shelf_id',
        'bin_id',
        'remarks',
    ];
}
