<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDF;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\Task;
use SkylarkSoft\GoRMG\TimeAndAction\Exports\TNAProgressReportExport;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAReports;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATask;
use SkylarkSoft\GoRMG\TimeAndAction\PackageConst;
use SkylarkSoft\GoRMG\TimeAndAction\Services\TNADelayEarlyCalculator;

class TNAProgressReportController extends Controller
{
    public function report(Request $request): JsonResponse
    {
        $data = $this->fetchReportData($request);
        return response()->json($data);
    }

    private function getDelayEarly($task): array
    {
        $tnaCalculator = new TNADelayEarlyCalculator($task);

        return [
            'delay' => $tnaCalculator->delay(),
            'early' => $tnaCalculator->early()
        ];
    }

    /**
     * @throws \Throwable
     */
    public function process(Request $request): JsonResponse
    {
        $request->validate(['*.id' => 'required']);
        try {
            \DB::beginTransaction();

            $requestedData = $request->all();
            $variableSettings = MerchandisingVariableSettings::query()
                ->where('factory_id', $requestedData[0]['factory_id'])
                ->where('buyer_id', $requestedData[0]['buyer_id'])
                ->first();

            $isPoWise = collect($variableSettings->variables_details)->get('tna_maintain') == TNAReports::PO_WISE;

            $editAbleId = array();
            $adjustAbleId = array();

            foreach ($requestedData as $key => $item) {
                $tnaReport = TNAReports::query()->with('task')->find($item['id']);
                $tnaReport->update($item);

                if ($tnaReport->task->plan_date_is_editable == 0 && $tnaReport->task->connected_task_id) {
                    $adjustAbleId[] = $tnaReport->id;
                }
                if ($tnaReport->task->plan_date_is_editable == 1) {
                    $editAbleId[] = $tnaReport->id;
                }
            }

            TNAReports::query()->with(['task.connectedTask.connectedTaskReport'])
                ->whereIn('id', $editAbleId)
                ->each(function ($report, $key) {
                    $finishDate = Carbon::parse($report->start_date)
                        ->addDays($report['execution_days'])
                        ->toDateString();

                    $report->update([
                        'finish_date' => $finishDate,
                    ]);
                });

            TNAReports::query()->with(['task.connectedTask.connectedTaskReport'])
                ->whereIn('id', $adjustAbleId)
                ->each(function ($report, $key) use ($isPoWise) {
                    $connectedStartTask = TNAReports::query()
                        ->with('task')
                        ->where('task_id', $report->task->connectedTask->id)
                        ->when($isPoWise, function ($query) use ($report) {
                            $query->where('po_id', $report->po_id);
                        }, function ($query) use ($report) {
                            $query->where('order_id', $report->order_id);
                        })
                        ->first();

                    $leadTimeWiseDays = collect($report->task->lead_time_wise_days)
                        ->where('lead_time', $report->lead_time)
                        ->first();

                    if ($leadTimeWiseDays && $connectedStartTask) {
                        $startDate = Carbon::parse($connectedStartTask->start_date)
//                            ->addDays($leadTimeWiseDays['days'])
                            ->toDateString();

                        $finishDate = Carbon::parse($startDate)
                            // ->addDays($report['execution_days'])
                            ->addDays($leadTimeWiseDays['days'])
                            ->toDateString();

                        $report->update([
                            'start_date' => $startDate,
                            'finish_date' => $finishDate,
                        ]);
                    }
                });

            \DB::commit();
            return response()->json(['message' => 'Processing Done!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Processing Failed!', 'errMsg' => $e->getMessage()], 400);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    private function fetchReportData(Request $request): array
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $orderId = $request->get('order_id');
        $poId = $request->get('po_id');
        $taskId = $request->get('task_id');
        $dealingMerchantId = $request->get('dealing_merchant_id');
        $searchType = $request->get('search_type');
        $shipFromDate = $request->get('ship_from_date');
        $shipEndDate = $request->get('ship_to_date');

        $data['factory'] = Factory::query()->find($factoryId);
        $data['buyer'] = Buyer::query()->find($buyerId);
        $data['order'] = Order::with('dealingMerchant', 'purchaseOrders')
            ->find($orderId)
            ->makeHidden('item_details');


        $data['reports'] = TNAReports::query()
            ->with([
                'task:id,task_name,task_short_name,group_id,plan_date_is_editable',
                'task.group:id,name,sequence'
            ])
            //->orderBy('task_sequence', 'asc')
            ->when($factoryId, function ($query) use ($factoryId) {
                $query->where('factory_id', $factoryId);
            })
            ->when($buyerId, function ($query) use ($buyerId) {
                $query->where('buyer_id', $buyerId);
            })
            ->when($orderId && !$poId, function ($query) use ($orderId) {
                $query->where('order_id', $orderId)
                    ->where('based_on', TNAReports::ORDER_WISE);
            })
            ->when($orderId && $poId, function ($query) use ($poId) {
                $query->where('po_id', $poId)
                    ->where('based_on', TNAReports::PO_WISE);
            })
            ->when($taskId && count($taskId), function ($q) use ($taskId) {
                $q->whereIn('task_id', $taskId);
            })
            ->when($dealingMerchantId, function ($q) use ($dealingMerchantId) {
                $q->whereHas('order', function ($query) use ($dealingMerchantId) {
                    $query->where('dealing_merchant_id', $dealingMerchantId);
                });
            })
            ->when($searchType && $shipFromDate && $shipEndDate,
                function ($q) use ($searchType, $shipFromDate, $shipEndDate) {
                    if ($searchType == 1) {
                        $q->whereHas('order', function ($query) use ($shipFromDate, $shipEndDate) {
                            $query->where('po_received_date', '>=', $shipFromDate);
                            $query->where('po_received_date', '<=', $shipEndDate);
                        });
                    } else {
                        $q->whereHas('order', function ($query) use ($shipFromDate, $shipEndDate) {
                            $query->where('shipment_date', '>=', $shipFromDate);
                            $query->where('shipment_date', '<=', $shipEndDate);
                        });
                    }
                })
            ->get()
            ->map(function ($task) {
                $data = collect($this->getDelayEarly($task))->merge($task->toArray());

                $data['start_date'] = $task->start_date ? date('d-m-Y', strtotime($task->start_date)) : null;
                $data['finish_date'] = $task->start_date ? date('d-m-Y', strtotime($task->finish_date)) : null;

                $data['actual_start_date'] = $task->actual_start_date ? date('d-m-Y', strtotime($task->actual_start_date)) : null;
                $data['actual_finish_date'] = $task->actual_start_date ? date('d-m-Y', strtotime($task->actual_finish_date)) : null;

                return $data;
            })
            ->groupBy('task.group.name');

        return $data;
    }

    public function sortProgressReportTaskSequence(Request $request): JsonResponse
    {
        $orderId = $request->get('order_id');
        $order = Order::query()->find($orderId);

        if ($order) {
            TNAReports::query()->where('order_id', $orderId)->get()
                ->each(function ($reportTask) {
                    $task = TNATask::query()->find($reportTask->task_id);
                    if ($task) {
                        $reportTask->update(['task_sequence' => $task->sequence]);
                    }
                });
        }

        return response()->json(['message' => 'Task Sorted Successfully!']);
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function pdf(Request $request)
    {
        $data = $this->fetchReportData($request);

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::loadView(PackageConst::VIEW . 'pdf.progress-report', $data);
            return $pdf->download('Progress Report.pdf');
        }

        if ($request->get('type') == 'xlsx') {
            return \Excel::download(new TNAProgressReportExport($data), 'tna-progress-report.xlsx');
        }
    }
}
