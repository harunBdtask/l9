<?php

namespace SkylarkSoft\GoRMG\Merchandising\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PoSendApprovalNotification extends Notification
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $data = $this->data;

        $url = '/approvals/modules/poApproval?factory_id=' . $data['factory_id'].'&buyer_id='.$data['buyer_id'];

        $title = 'You have PO approve request for <span class="text-primary"> ' . $data['po_no'] . ' </span> in order (<span class="text-primary"> ' . $data['order_no'] . ' </span>)';

        return [
            'url' => $url,
            'title' => $title,
            'body' => '',
            'module' => 'merchandising',
            'job_no' => $data['order_no'],
        ];
    }
}
