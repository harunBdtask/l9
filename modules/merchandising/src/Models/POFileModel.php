<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class POFileModel extends Model
{
    use SoftDeletes;

    protected $table = "po_files";
    protected $primaryKey = "id";
    protected $fillable = [
        'buyer_id',
        'buyer_code',
        'po_no',
        'style',
        'flag',
        'file',
        'processed',
        'used',
        'is_read',
        'quantity_matrix',
        'file_issues',
        'po_quantity'
    ];

    protected $casts = [
        "quantity_matrix" => Json::class,
        "file_issues" => Json::class,
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_no', 'po_no')
            ->withDefault();
    }
}
