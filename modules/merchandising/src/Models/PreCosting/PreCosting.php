<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\PreCosting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class PreCosting extends Model
{
    use HasFactory;

    protected $table = 'pre_costings';

    protected $fillable = [
        'factory_id',
        'buyer_id',
        'season_id',
        'style',
        'customer',
        'item_id',
        'create_date',
        'revise_date',
        'costing_status',
        'tp_file',
        'costing_file',
        'tp_file_2',
        'tp_file_3',
        'costing_file_2',
        'costing_file_3',
        'remarks',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id')->withDefault();
    }

    public function item()
    {
        return $this->belongsTo(GarmentsItem::class, 'item_id')->withDefault();
    }
}
