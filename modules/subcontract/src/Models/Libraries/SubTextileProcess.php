<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTextileProcess extends Model
{
    use CommonModelTrait;
    use SoftDeletes;
    use BelongsToFactory;

    protected $table = "sub_textile_processes";
    protected $fillable = [
        "factory_id",
        "sub_textile_operation_id",
        "name",
        "price",
        "status",
    ];

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function textileOperation(): BelongsTo
    {
        return $this->belongsTo(SubTextileOperation::class, 'sub_textile_operation_id')->withDefault();
    }
}
