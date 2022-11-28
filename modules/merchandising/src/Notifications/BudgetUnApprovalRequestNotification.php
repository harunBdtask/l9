<?php

namespace SkylarkSoft\GoRMG\Merchandising\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BudgetUnApprovalRequestNotification extends Notification
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
        $title = "You've a request for unapprove budget. For job no (<span class='text-primary'> {$data['job_no']} </span>)";
        $url = '/approvals/modules/budget?' . http_build_query([
                'factory_id' => $data['factory_id'],
                'buyer_id' => $data['buyer_id'],
                'job_no' => $data['job_no'],
                'approval_type' => 2,
            ]);

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
