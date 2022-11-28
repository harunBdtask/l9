<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use App\Models\BelongsToCreatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class POFileLog extends Model
{
    use SoftDeletes, ModelCommonTrait, BelongsToCreatedBy;

    protected $table = "po_file_logs";
    protected $primaryKey = "id";
    protected $fillable = [
        'buyer_id',
        'po_file_id',
        'po_no',
        'style',
        'quantity_matrix',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'updated_by',
    ];

    protected $casts = [
        "quantity_matrix" => Json::class,
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }
}
