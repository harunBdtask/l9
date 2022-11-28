<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Services\Approval\ApproveService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;
use Symfony\Component\HttpFoundation\Response;

class TrimsBudgetController extends Controller
{
    public function trimsItemSizeWiseBreakdown($budgetId, Request $request): JsonResponse
    {
        $request['cons_gmts'] = collect(array_get($request->all(), 'breakdown.details', []))
            ->average('cons_gmts');

        $request['ex_cons_percent'] = collect(array_get($request->all(), 'breakdown.details', []))
            ->average('ex_cons_percent');

        $breakdown = ApproveService::checkFor(POItemColorSizeBreakdownService::trims($budgetId, $request))
            ->variableCheckByBudget($budgetId)
            ->get();

        return response()->json([
            'status' => 'success',
            'breakdown' => $breakdown,
        ]);
    }

    public function saveFile(Request $request)
    {
        $request->validate([
            'file' => 'mimes:jpg,bmp,png',
        ]);

        try {
            $filePath = null;
            $destination = $request->input('destination') ?: '';

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePath = Storage::put($destination, $file);
            }

            if ($request->has('prevFile') && $prevFilePath = $request->input('prevFile')) {
                Storage::delete($prevFilePath);
            }

            return response()->json(['status' => 'success', 'file' => $filePath]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
