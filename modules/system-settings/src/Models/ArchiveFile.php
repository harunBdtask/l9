<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class ArchiveFile extends Model
{
    use CommonModelTrait;

    protected $table = 'archive_files';
    protected $fillable = [
        'file',
        'style',
        'remarks',
        'style_id',
        'buyer_id',
        'file_name',
        'factory_id',
        'archive_type',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }
    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'style_id')->withDefault();
    }
}
