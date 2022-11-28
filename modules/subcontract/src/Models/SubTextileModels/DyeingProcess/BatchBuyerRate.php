<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchBuyerRate extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = "batch_buyer_rate";
    protected $primaryKey = "id";
    protected $fillable = [
      'batch_id',
      'dia_type_id',
      'rate',
    ];
}
