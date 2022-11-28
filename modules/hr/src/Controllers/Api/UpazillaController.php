<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrUpazilla;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UpazillaController extends Controller
{
    public function getUpazillas() {
        try {
            $upazillas = HrUpazilla::query()->get();
            return response()->json($upazillas, 200);
        }
        catch(\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getUpazillasByZillaId($zillaId) {
        $upazillas = HrUpazilla::where('district_id', $zillaId)->get();

        return response()->json($upazillas, 200);
    }
}
