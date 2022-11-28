<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrZilla;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ZillaController extends Controller
{
    public function getZillas() {
        try {
            $zillas = HrZilla::query()->get();
            return response()->json($zillas, 200);
        }
        catch(\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
