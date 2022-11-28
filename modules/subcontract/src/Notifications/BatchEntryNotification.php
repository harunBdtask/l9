<?php

namespace SkylarkSoft\GoRMG\Subcontract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BatchEntryNotification extends Notification
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
        return (new MailMessage())
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
        $url = '/subcontract/dyeing-process/batch-entry/view/' . $this->data['id'];

        if ($this->data['type'] == 'created') {
            $title = "A new sub dyeing batch( <span class='text-primary'>" . $this->data['batch_no'] . " </span>) has been created.";
        } elseif ($this->data['type'] == 'updated') {
            $title = "Sub dyeing batch( <span class='text-primary'>" . $this->data['batch_no'] . " </span>)
                      order no( <span class='text-primary'>" . $this->data['order_nos'] . " </span>)
                      weight( <span class='text-primary'>" . $this->data['batch_weight'] . " </span>) has been updated.
                      Changes: <span class='text-primary'>".$this->data['changes']."</span>";
        } elseif ($this->data['type'] == 'detail_deleted') {
            $title = "Sub dyeing batch ( <span class='text-primary'>" . $this->data['batch_no'] . " </span>) detail has been deleted.";
        } else {
            $title = "Sub dyeing batch ( <span class='text-primary'>" . $this->data['batch_no'] . " </span>) has been deleted.";
            $url = '/subcontract/dyeing-process/batch-entry';
        }

        return [
            'url' => $url,
            'title' => $title,
        ];
    }
}
