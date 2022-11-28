<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class IncotermPlace extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $table = "incoterm_places";
    protected $fillable = [
        'incoterm_place',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = Auth::user()->factory_id;
            $post->created_by = Auth::user()->id;
        });
        static::updating(function ($post) {
            $post->updated_by = Auth::user()->id;
        });
        static::deleting(function ($post) {
            $post->deleted_by = Auth::user()->id;
            $post->save();
        });
    }

    public function factory()
    {
        return $this->belongsTo(
            'Skylarksoft\Systemsettings\Models\Factory',
            'factory_id',
            'id'
        );
    }

    public function quotationMaster()
    {
        return $this->hasMany(
            'SkylarkSoft\GoRMG\Merchandising\Models\QuotationMaster',
            'incotern_place',
            'id'
        );
    }

    public function preparedBy()
    {
        return $this->belongsTo(
            'Skylarksoft\Systemsettings\Models\User',
            'created_by',
            'id'
        );
    }

    public function edittedBy()
    {
        return $this->belongsTo(
            'Skylarksoft\Systemsettings\Models\User',
            'updated_by',
            'id'
        );
    }

    public function deletedByUser()
    {
        return $this->belongsTo(
            'Skylarksoft\Systemsettings\Models\User',
            'deleted_by',
            'id'
        );
    }
}
