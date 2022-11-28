<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;
    protected $table = "parties";
    protected $fillable = [
        'party_name',
        'party_type_id',
        'factory_id',
    ];
    protected $dates = ['deleted_at'];

    public function party_types()
    {
        return $this->belongsTo(PartyType::class, 'party_type_id', 'id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }
}
