<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BondedWarehouse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'bonded_warehouses';

    protected $fillable = [
        'name'
    ];
    protected $dates = ['deleted_at'];

    protected $appends = [
        'text'
    ];

    public function getTextAttribute()
    {
        return $this->name;
    }
}
