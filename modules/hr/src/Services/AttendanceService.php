<?php

namespace SkylarkSoft\GoRMG\HR\Services;

use Illuminate\Support\Facades\Http;

class AttendanceService
{
    public static function getAttendanceFromMdb()
    {
        $response = Http::get(config('app.attendance_rpc'));
        return $response->json();

    }
}
