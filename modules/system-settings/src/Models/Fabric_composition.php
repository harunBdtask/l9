<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fabric_composition extends Model
{
    use SoftDeletes;
    protected $table = 'fabric_composition';
    protected $fillable = [
        'yarn_composition',
        'factory_id',
        'status_active',
    ];

    protected $cascadeDeletes = ['knittingAllocationDetails'];

    public function knittingAllocationDetails()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnittingAllocationDetail', 'fabric_composition_id', 'id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }
}
