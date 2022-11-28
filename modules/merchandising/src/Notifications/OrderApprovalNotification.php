<?php

namespace SkylarkSoft\GoRMG\Merchandising\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderApprovalNotification extends Notification
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
        $title = "You've a new order {$this->data['approval_type']}  request. For job no (<span class='text-primary'> {$this->data['job_no']} </span>)";
        $url = '/approvals/modules/order-approval?' . http_build_query([
                'approval_type' => $this->data['approval_type'] == 'unapprove' ? 2 : 1,
                'factory_id' => $this->data['factory_id'],
                'buyer_id' => $this->data['buyer_id'],
                'job_no' => $this->data['job_no'],
            ]);

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
