<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use SkylarkSoft\GoRMG\Merchandising\Models\POFileLog;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;

class POFileLogAction
{
    public static function handle($POFileModel, $remarks)
    {
        $log = array_merge($POFileModel->toArray(), [
            'po_file_id' => $POFileModel['id'],
            'remarks' => $remarks,
        ]);

        POFileLog::query()->create($log);
    }
}
