<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechPackFile extends Model
{
    use SoftDeletes;

    protected $table = 'tech_pack_files';
    protected $primaryKey = 'id';
    protected $fillable = [
        'style',
        'file',
        'creeper_count',
        'body_part_count',
        'processed',
        'edit_status',
        'used',
        'contents',
    ];

    public function getContentsAttribute($value)
    {
        return json_decode($value);
    }
}
