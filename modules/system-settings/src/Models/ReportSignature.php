<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Casts\Json;
use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\PageReportNameConst;

class ReportSignature extends Model
{
    use CommonModelTrait, BelongsToFactory, BelongsToBuyer;

    protected $table = 'report_signatures';
    protected $fillable = [
        'factory_id',
        'buyer_id',
        'page_name',
        'view_button',
        'is_active',
        'is_template',
        'template_name'
    ];

    protected $appends = [
        'page_name_value',
        'view_button_value',
        'buyer_names',
    ];

    protected $cast = [
        'buyer_id' => Json::class,
    ];

    public function getPageNameValueAttribute(): string
    {
        return isset($this->attributes['page_name']) ? array_search($this->attributes['page_name'], PageReportNameConst::PAGES) : '';
    }

    public function getViewButtonValueAttribute(): string
    {
        return isset($this->attributes['view_button']) && $this->attributes['view_button'] === 1 ? 'Single' : 'Multiple';
    }

    public function scopeIsNotTemplate($query)
    {
        $query->where('is_template', 0);
    }

    public function getBuyerNamesAttribute()
    {
        if (isset($this->attributes['buyer_id'])) {
            $checkBuyerIds = $this->attributes['buyer_id'];

            if (gettype($this->attributes['buyer_id']) !== 'array') {
                $checkBuyerIds = json_decode($this->attributes['buyer_id'], true);
            }
    
            if (is_array($checkBuyerIds)) {
                $buyerIds = gettype($this->attributes['buyer_id']) == "string" ? 
                    array_map('intval', json_decode($this->attributes['buyer_id'], true))
                    : $this->attributes['buyer_id'];
    
                return Buyer::query()
                        ->whereIn('id', $buyerIds)
                        ->pluck('name');
            }
            return Buyer::query()
                ->where('id', $this->attributes['buyer_id'])
                ->pluck('name') ?? "[]";
        }
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReportSignatureDetail::class, 'report_signature_id');
    }
}
