<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class TrimsAccessoryDetail extends Model
{
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $table = 'trims_accessory_details';

    protected $fillable = [
        'order_id',
        'trims_accessory_id',
        'style_description',
        'vendor_code',
        'item_id',
        'color_id',
        'size_id',
        'size_wise',
        'percentage',
        'color_hint',
        'item_description',
        'fabric_composition_id',
        'production_batch_no',
        'care_instruction_symbol_image',
        'special_instruction',
        'quantity',
        'unit_price',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $cascadeDeletes = [
        'careInstructionSymboolImages',
    ];

    public function color()
    {
        return $this->belongsTo(Color::class)->withDefault();
    }

    public function size()
    {
        return $this->belongsTo(Size::class)->withDefault();
    }

    public function fabricComposition()
    {
        return $this->belongsTo(Fabric_composition::class, 'fabric_composition_id')->withDefault();
    }

    public function careInstructionSymboolImages()
    {
        return $this->hasMany(CareInstructionSymboolImage::class, 'trims_accessory_detail_id');
    }
}
