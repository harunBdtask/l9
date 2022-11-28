<?php

namespace SkylarkSoft\GoRMG\Merchandising\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FabricBookingApprovalNotification extends Notification
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
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $data = $this->data;
        $title = "You've a new fabric booking {$data['approval_type']} request. For booking no (<span class='text-primary'> {$data['unique_id']} </span>)";
        $url = '/approvals/modules/fabric-booking?' . http_build_query([
                'approval_type' => $data['approval_type'] == 'unapprove' ? 2 : 1,
                'factory_id' => $data['factory_id'],
                'unique_id' => $data['unique_id'],
                'buyer_id' => $data['buyer_id'],
            ]);

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
