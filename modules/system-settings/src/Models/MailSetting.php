<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    protected $table = "mail_settings";
    protected $primaryKey = "id";
    protected $fillable = [
        "mail_type", "receiver_groups"
    ];

    protected $casts = [
        "receiver_groups" => 'array',
    ];

    protected $appends = [
        'groups_name',
        'mail_type_value'
    ];

    public const MAIL_TYPE = [
        'order_entry' => 'Order entry',
        'order_confirmation' => 'Order confirmation',
        'daily_cutting_report' => 'Daily cutting report',
        'daily_input_report' => 'Daily input report',
        'daily_sewing_report' => 'Daily sewing report',
        'daily_finishing_report' => 'Daily finishing report',
        'daily_order_po_report' => 'Daily Order Received Update',
        'po_shipment_reminder' => 'Today Delivery ( Export Orders )',
        'daily_yarn_received_statement' => 'Daily Yarn Receive Statement',
        'daily_yarn_issue_report' => 'Daily Yarn Issue Report',
        'daily_finish_fabric_receive' => 'Daily Finish Fabric Receive Report',
        'daily_finish_fabric_issue' => 'Daily Finish Fabric Issue Report',
        'daily_cutting_report_v2' => 'Daily Cutting Report V2',
        'daily_print_embr' => 'Daily Print Embr. Report',
        'daily_sewing_input_update' => 'Daily Sewing Input Report',
        'daily_output_report' => 'Daily Output Report',
        'hourly_finishing_production_report' => 'Hourly Finishing Production Report',
        'daily_finishing_production_report_v3' => 'Daily Finishing Production Report V3',
    ];

    public function getGroupsNameAttribute(): string
    {
        return is_array($this->receiver_groups) ?
            MailGroup::query()
                ->whereIn('id', $this->receiver_groups)
                ->pluck('name')
                ->implode(', ') : '';
    }

    public function getMailTypeValueAttribute(): string
    {
        return self::MAIL_TYPE[$this->mail_type] ?? '';
    }
}
