<?php

namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Services\PrintSendChallanCutManagerService;
use Symfony\Component\HttpFoundation\Response;

class PrintSendChallanCutManagerController extends Controller
{
    const PAGE_NAME =  'Print Send Challan Approval(Cutting Manager)';
    
    public function index()
    {
        return view('approval::approvals.modules.print_send_challan_cut_manager');
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = (new PrintSendChallanCutManagerService)
                ->setRequest($request)
                ->response();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = (new PrintSendChallanCutManagerService)
                ->setRequest($request)
                ->store();
            DB::commit();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}