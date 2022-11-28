<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;

class SampleDetail extends Model
{
    use SoftDeletes;

    protected $table = 'sample_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sample_id',
        'item_id',
        'fabric_description',
        'composition_fabric_id',
        'item_description',
        'gsm',
        'fabrication',
        'unit_price',
        'factory_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function sample_development()
    {
        return $this->belongsTo(Sample::class, 'sample_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(GarmentsItem::class, 'item_id', 'id');
    }

    public function fabrication()
    {
        return $this->belongsTo(NewFabricComposition::class, 'composition_fabric_id', 'id');
    }
}
