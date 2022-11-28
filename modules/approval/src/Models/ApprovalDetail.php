<?php

namespace SkylarkSoft\GoRMG\Approval\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\ReportSignatureDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalDetail extends Model
{
    use ModelCommonTrait;

    protected $table = 'approval_details';
    protected $fillable = [
        'factory_id', 'approval_detailable_id', 'approval_detailable_type', 'user_id', 'page_name', 'priority', 'type'
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault([
            'factory_name' => 'N/A',
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
