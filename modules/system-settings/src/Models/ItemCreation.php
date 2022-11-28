<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCreation extends Model
{
    protected $table = 'item_creations';
    protected $fillable = ['factory_id', 'item_group_id', 'sub_group_code', 'sub_group_name', 'item_code', 'item_description', 'item_size', 're_order_label', 'min_label', 'max_label', 'status'];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function itemGroup()
    {
        return $this->belongsTo(ItemGroup::class, 'item_group_id');
    }
}
