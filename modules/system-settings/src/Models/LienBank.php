<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LienBank extends Model
{
    use SoftDeletes;

    protected $table = 'lien_banks';

    protected $fillable = [
        'name', 'address', 'contact_person', 'created_by', 'updated_by', 'deleted_by', 'swift_code'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = Auth::id();
        });
        static::updating(function ($post) {
            $post->updated_by = Auth::id();
        });
        static::deleting(function ($post) {
            $post->deleted_by = Auth::id();
        });
    }

    public function buyers()
    {
        return $this->belongsToMany(Buyer::class);
    }
}
