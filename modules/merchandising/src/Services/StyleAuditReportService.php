<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\StyleAuditReport;

class StyleAuditReportService
{
    public static function generateReport($style_id)
    {
        return StyleAuditReport::query()
            ->with('order')
            ->where('style_id', $style_id)
            ->first();
    }
}
