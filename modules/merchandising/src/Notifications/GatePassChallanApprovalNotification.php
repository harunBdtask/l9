<?php

namespace SkylarkSoft\GoRMG\Merchandising\Notifications;

use Illuminate\Notifications\Notification;

class GatePassChallanApprovalNotification extends Notification
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $title = "You've a new Gatepass Challan {$this->data['approval_type']} request. For challan no (<span class='text-primary'> {$this->data['challan_no']} </span>)";
        $url = '/approvals/modules/gate-pass-challan?' . http_build_query([
                'approval_type' => $this->data['approval_type'] == 'unapprove' ? 2 : 1,
                'factory_id' => $this->data['factory_id'],
                'buyer_id' => $this->data['buyer_id'],
                'challan_no' => $this->data['challan_no'],
            ]);

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
