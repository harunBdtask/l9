<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeWorkType;

class WorkTypeController extends Controller
{
    public function getWorkTypes() {
        try {
            $workTypes = HrEmployeeWorkType::query()->get();
            return response()->json($workTypes, 200);
        }
        catch(\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
