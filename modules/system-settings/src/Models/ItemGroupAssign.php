<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemGroupAssign extends Model
{
    use SoftDeletes;

    protected $table = 'items_group_assign';
    protected $primary_key = 'id';
    protected $fillable = [
        'item_id',
        'factory_id',
        'item_group_id',
        'status',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_by',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function factory()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\Factory',
            'factory_id',
            'id'
        );
    }

    public function item()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\Item',
            'item_id',
            'id'
        );
    }

    public function group()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup',
            'item_group_id',
            'id'
        );
    }

    public function prepared_by()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'created_by',
            'id'
        );
    }

    public function editted_by()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'updated_by',
            'id'
        );
    }

    public function deleted_by_user()
    {
        return $this->belongsTo(
            'SkylarkSoft\GoRMG\SystemSettings\Models\User',
            'deleted_by',
            'id'
        );
    }
}
