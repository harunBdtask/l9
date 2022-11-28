<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PrintSendChallanCutManagerNotification extends Notification
{
    use Queueable;

    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $thisData = $this->data;

        $title = "You've a new print gatepass challan approve request. For challan no (<span class='text-primary'> {$thisData['challan_no']} </span>)";
        $url = '/approvals/modules/print-send-challan-cut-manager?' . http_build_query([
                'factory_id' => $thisData['factory_id'],
                'challan_no' => $thisData['challan_no'],
                'approval_type' => 1,
            ]);
        
        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
