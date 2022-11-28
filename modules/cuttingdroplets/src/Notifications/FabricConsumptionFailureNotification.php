<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FabricConsumptionFailureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $url;
    private $summaryReport;
    private $bundleCardDetails;

    public function __construct($url, $bundleCardDetails, $summaryReport)
    {
        $this->url = $url;
        $this->summaryReport = $summaryReport;
        $this->bundleCardDetails = $bundleCardDetails;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Consumption Fail Approval')
            ->view(
                'cuttingdroplets::mail-notifications.fabric-cons-failure-mail',
                [
                    'data' => $this->bundleCardDetails,
                    'summaryReport' => $this->summaryReport,
                    'url' => $this->url,
                ]
            );
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
