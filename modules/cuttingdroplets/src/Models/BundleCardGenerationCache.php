<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BundleCardGenerationCache extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bg_id',
        'sid',
        'details'
    ];

    protected $casts = [
        'details' => Json::class
    ];

    protected $dates = ['deleted_at'];

    public function bundleCardGenerationDetail()
    {
        return $this->belongsTo(BundleCardGenerationDetail::class, 'id', 'id')->withDefault();
    }
}
