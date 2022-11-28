<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        "notification_type", "receiver_groups"
    ];

    protected $casts = [
        "receiver_groups" => 'array',
    ];

    protected $appends = [
        'groups_name',
        'notification_type_value'
    ];

    public const NOTIFICATION_TYPE = [
        'dyeing_process_batch_entry' => 'Dyeing Process Batch Entry',
        'sub_goods_delivery' => 'Sub Goods Delivery',
    ];

    public function getGroupsNameAttribute(): string
    {
        return is_array($this->receiver_groups) ?
            NotificationGroup::query()
                ->whereIn('id', $this->receiver_groups)
                ->pluck('name')
                ->implode(', ') : '';
    }

    public function getNotificationTypeValueAttribute(): string
    {
        return self::NOTIFICATION_TYPE[$this->notification_type] ?? '';
    }
}
