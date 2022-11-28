<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Listeners;

use SkylarkSoft\GoRMG\TimeAndAction\Events\TNAReportProcess;
use SkylarkSoft\GoRMG\TimeAndAction\Services\TNAReportService;

class ReportProcessFromOrder
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(TNAReportProcess $event)
    {
        (new TNAReportService)->dataAssignToReportTable($event->order);
    }
}
