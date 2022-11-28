<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class EmbellishmentItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "embellishment_items";
    protected $fillable = [
        'name',
        'type',
        'tag',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $dates = ["deleted_at"];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        static::saving(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        static::updating(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'updated_by' => auth()->user()->id,
            ]);
        });
        static::deleting(function ($model) {
            DB::table($model->table)->where('id', $model->id)->update([
                'deleted_by' => auth()->user()->id,
            ]);
        });
    }

    const EMBL_NAMES = [
        'Printing' => 'Printing',
        'Embroidery' => 'Embroidery',
        'Special Works' => 'Special Works',
        'Gmts Dyeing' => 'Gmts Dyeing',
        'Wash' => 'Wash',
    ];
    const PRINTING = 'Printing';
    const EMBROIDERY = 'Embroidery';
    const SPECIAL_WORKS = 'Special Works';
    const GMTS_DYEING = 'Gmts Dyeing';
    const WASH = 'Wash';
}
