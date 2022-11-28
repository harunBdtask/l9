<?php

namespace SkylarkSoft\GoRMG\DyesStore\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DyesReceiveApprovalNotification extends Notification
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
        $title = "You've a new dyes chemical receive {$this->data['approval_type']}  request for receive no (<span class='text-primary'> {$this->data['receive_no']} </span>)";
        $url = '/approvals/modules/dyes-chemical-store?' . http_build_query([
                'approval_type' => $this->data['approval_type'] == 'unapprove' ? 2 : 1,
                'receive_no' => $this->data['receive_no'],
            ]);

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
