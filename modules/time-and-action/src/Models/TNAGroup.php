<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TNAGroup extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'tna_groups';

    protected $fillable = [
        'name', 'sequence', 'created_by', 'updated_by', 'deleted_by', 'factory_id'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(TNATask::class, 'group_id', 'id');
    }
}
