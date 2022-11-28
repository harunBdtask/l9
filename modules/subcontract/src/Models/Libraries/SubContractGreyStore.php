<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SubContractGreyStore extends Model
{
    use SoftDeletes;
    use BelongsToFactory;

    protected $table = 'sub_grey_store';
    protected $primaryKey = 'id';
    protected $fillable = [
            'factory_id',
            'name',
            'created_by',
            'updated_by',
            'deleted_by',
    ];

    protected static function booted()
    {
        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleting(function ($model) {
            $model->deleted_by = Auth::id();
        });
    }
}
