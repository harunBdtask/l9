<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
	use SoftDeletes;

    protected $table = 'parts';

    protected $fillable = [
    	'name',
    ];

    protected $dates = ['deleted_at'];

    public function bundleCardGenerationDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Skylarksoft\Cuttingdroplets\Models\BundleCardGenerationDetail', 'part_id', 'id');
    }

    public function printInventoryChallans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Skylarksoft\Printembrdroplets\Models\PrintInventoryChallan', 'part_id', 'id');
    }
}
