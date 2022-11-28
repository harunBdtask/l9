<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CuttingInventoryNotification extends Notification
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
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
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
    public function toArray($notifiable)
    {
        $message = $this->data;
        $challan_no = $message['challan_no'];
        $factory_id = $message['factory_id'];
        $url = '/approvals/modules/sewing-input-challan-cut-manager?factory_id='.$factory_id.'&challan_no='.$challan_no.'&approval_type=1&page=1';

        $title = 'You\'ve a new sewing input challan approve request; for Challan no (<span class="text-primary"> ' . $challan_no . ' </span>)';

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
