<?php

namespace SkylarkSoft\GoRMG\McInventory\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MachineServiceDateNotification extends Notification
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
     * @param $notifiable
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
     * @param $notifiable
     * @return string[]
     */
    public function toArray($notifiable): array
    {
        $notifyData = $this->data;
        $url = '/mc-inventory/date-wise-machine-maintenance?' . http_build_query([
                'date' => $notifyData->next_maintenance,
            ]);
        $title = 'Today <span class="text-primary"> ' . $notifyData->machine->name . ' </span> Service Date And Barcode No (<span class="text-primary"> ' . $notifyData->machine->barcode . ' </span>)';
        return [
            'url' => $url,
            'title' => $title
        ];
    }
}
