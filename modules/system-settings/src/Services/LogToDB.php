<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\Log;

class LogToDB
{
    /**
     * @param array $data
     */
    public static function log(array $data)
    {
        $log = new Log();
        $log->fill($data)->save();
    }
}
