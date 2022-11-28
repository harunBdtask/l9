<?php


namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\DyeingCompany;

class FetchDyeingCompanyController extends Controller
{
    public function __invoke()
    {
        try {
            $dyeingCompany = DyeingCompany::query()->get([
                'id',
                'name as text'
            ]);
            return response()->json($dyeingCompany);
        } catch(Exception $exception){
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}