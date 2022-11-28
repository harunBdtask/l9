<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrPostOffice;
use Symfony\Component\HttpFoundation\Response;

class PostAddressCodeController extends Controller
{
    public function getPostOfficesByZilla($zillaId) {
        try {
            $zillas = HrPostOffice::query()
                ->where('district_id', $zillaId)
                ->get();

            return response()->json($zillas, 200);
        }
        catch(\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

}
