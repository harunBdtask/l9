<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\TimeAndAction\Exports\TNAReportExport;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAReports;
use SkylarkSoft\GoRMG\TimeAndAction\Services\TNAReportService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class TNAReportController extends Controller
{
    /**
     * @throws \Exception
     */
    public function reportDispatch(Request $request): JsonResponse
    {
        $orderId = $request->get('order_id');
        $poId = $request->get('po_id');
        if (!$poId && $orderId) {
            $message = (new TNAReportService)->dataAssignToReportTable($orderId);
            return response()->json($message, Response::HTTP_OK);
        } else if (!$orderId && $poId) {
            $message = (new TNAReportService)->dataAssignToReportTableForPOWise($poId);
            return response()->json($message, Response::HTTP_OK);
        } else {
            throw new \Exception(
                "Lead Time Not Found For Order Id $orderId & PO Id $poId",
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function search(Request $request): JsonResponse
    {
        $data = (new TNAReportService())->search($request);
        $variableSettings = $this->getVariableSettings($request->get('factory_id'), $request->get('buyer_id'));
        $data = [
            'data' => $data,
            'variable_settings' => $variableSettings
        ];
        return response()->json($data, Response::HTTP_OK);
    }

    public function getSearchRelatedData(Request $request): JsonResponse
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');

        $order = Order::query()
            ->with(['purchaseOrders', 'dealingMerchant'])
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->get();

        $tasks = TNAReports::query()->with('task')
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->get()
            ->unique('task_id');

        return response()->json([
            'order' => $order,
            'tasks' => $tasks
        ], Response::HTTP_OK);
    }

    public function getOrder(Request $request): JsonResponse
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');

        $order = Order::query()
            ->with(['purchaseOrders' => function ($q) {
                $q->distinct();
            }, 'dealingMerchant' => function ($q) {
                $q->distinct();
            }])
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->get();

        return response()->json($order, Response::HTTP_OK);
    }

    public function getVariableSettings($factoryId, $buyerId): int
    {
        $variableSettings = MerchandisingVariableSettings::query()
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->first();

        return (int)collect($variableSettings->variables_details)->get('tna_maintain');
    }

    public function updateTask($id, Request $request): JsonResponse
    {
        TNAReports::query()->find($id)->update($request->except('id'));
        $data = TNAReports::query()->find($id);
        return response()->json([
            'data' => $data,
            'payload' => $request->all()
        ], Response::HTTP_OK);
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function reportExcel(Request $request): BinaryFileResponse
    {
        $request->merge([
            'task_id' => $request->get('task_id') ? explode(',', $request->get('task_id')) : null,
        ]);
        $data = (new TNAReportService())->search($request);
        $variable = $this->getVariableSettings($request->get('factory_id'), $request->get('buyer_id'));
//        return view('time-and-action::excel.report', compact('data', 'variable'));
        return Excel::download(new TNAReportExport($data, $variable), 'time-and-action.xlsx');
    }
}
