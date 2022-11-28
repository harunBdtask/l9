<?php
namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class FloorServices
{
    public static function getAllFloors()
    {
        return Floor::all();
    }
}
