<?php

namespace SkylarkSoft\GoRMG\Merchandising\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use SkylarkSoft\GoRMG\TimeAndAction\Services\TNAReportService;

class TnaReportProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $tnaService = new TNAReportService();
        $variable = $tnaService->variable($this->order->factory_id, $this->order->buyer_id)->variables_details;
        if ((!$variable || collect($variable)->get('tna_maintain') != 1) &&
            $this->order->shipment_date &&
            $this->order->po_received_date && $this->order->lead_time) {
            $tnaService->dataAssignToReportTable($this->order->id);
        }
    }
}
