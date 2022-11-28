<?php
namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\Line;


class LineServices
{
    public static function getAllLines($id)
    {
        return Line::where('floor_id', $id)->get();
    }
    
    public static function getAllLinesForDropdown($id)
    {
        return Line::where('floor_id', $id)->pluck('line_no', 'id');
    }
}
