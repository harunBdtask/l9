<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services;

use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATemplate;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATemplateDetail;

class TemplateCopyService
{
    public function copy($request) : void
    {
        $templateId = (int)$request->get('template_id');
        $leadTime = (int)$request->get('lead_time');
        $template = TNATemplate::query()->with('details')->find($templateId);
        $newTemplate = $this->getNewTemplate($template, $leadTime);
        $this->newTemplateDetailsSave($template, $newTemplate, $leadTime);
    }

    private function getNewTemplate($template, int $leadTime)
    {
        $newTemplate = $template->only(['factory_id', 'buyer_id', 'tna_for']);
        $newTemplate['lead_time'] = $leadTime;
        return TNATemplate::query()->create($newTemplate);
    }

    private function newDeadline($oldLeadTime, $newLadTime, $oldDeadLine): int
    {
        return round(($oldDeadLine / $oldLeadTime) * $newLadTime);
    }

    private function newExecutionDays($oldLeadTime, $newLadTime, $oldExecutionDays): int
    {
        return round(($oldExecutionDays / $oldLeadTime) * $newLadTime);
    }

    private function newTemplateDetailsSave($template, $newTemplate, int $leadTime): void
    {
        foreach ($template['details'] as $detail) {
            $date['template_id'] = $newTemplate->id;
            $date['task_id'] = $detail->task_id;
            $date['deadline'] = null; //$this->newDeadline($template->lead_time, $leadTime, $detail->deadline);
            $date['execution_days'] = $this->newExecutionDays($template->lead_time, $leadTime, $detail->execution_days);
            $date['start_from_day_no'] = $detail->start_from_day_no;
            $date['notice_before'] = $detail->notice_before;
            $date['task_sequence'] = $detail->task_sequence;
            $date['status'] = $detail->status;
            TNATemplateDetail::query()->create($date);
        }
    }
}
