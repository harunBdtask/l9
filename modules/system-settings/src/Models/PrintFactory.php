<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintFactory extends Model
{
    use SoftDeletes;

    protected $table = 'print_factories';

    protected $fillable = [
        'factory_type',
        'group_name',
        'factory_name',
        'factory_short_name',
        'factory_address',
        'responsible_person',
        'phone_no',
        'factory_id',
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->factory_id = Auth::user()->factory_id;
        });
    }
}
