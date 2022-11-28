<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services;

use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATemplate;

class TNATemplateSelector
{
    public function template($data)
    {
        return TNATemplate::query()
            ->with('details.task')
            ->where('factory_id', $data->factory_id)
            ->where('buyer_id', $data->buyer_id)
            ->orderBy('lead_time', 'asc')
            ->get()->filter(function ($template) use ($data) {
                return $template->lead_time >= $data->lead_time;
            })->first();

//        return TNATemplate::query()
//            ->with('details.task')
//            ->where('factory_id', $data->factory_id)
//            ->where('buyer_id', $data->buyer_id)
//            ->where('lead_time', '<=', $data->lead_time)
//            ->orderBy('lead_time', 'desc')
//            ->first();
    }
}
