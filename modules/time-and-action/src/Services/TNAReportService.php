<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services;

use _PHPStan_9a6ded56a\Nette\Neon\Exception;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAReports;
use SkylarkSoft\GoRMG\TimeAndAction\Notifications\TNATaskNotification;
use Throwable;

class TNAReportService
{

    /**
     * @var TNATemplateSelector
     */
    private $templateSelector;

    /**
     * @var TNAPreviousDataDelete
     */
    private $previousDataRemover;
    /**
     * @var TNAReportTableDataFormatter
     */
    private $reportTableFormatter;

    public function __construct()
    {
        $this->templateSelector = new TNATemplateSelector;
        $this->previousDataRemover = new TNAPreviousDataDelete;
        $this->reportTableFormatter = new TNAReportTableDataFormatter;
    }

    public function dataAssignToReportTable($orderId): array
    {
        try {
            DB::beginTransaction();

            $order = Order::query()->find($orderId);
            $template = $this->templateSelector->template($order);
            $this->previousDataRemover->deleteForStyle($order);

            foreach ($template['details'] as $detail) {
                $data = $this->reportTableFormatter->formatForStyle([
                    'details' => $detail,
                    'order' => $order,
                    'template' => $template
                ]);
                TNAReports::query()->create($data);
                TNANotificationService::notification($detail, [
                    'job_no' => $order->job_no,
                    'buyer_id' => $order->buyer_id,
                    'factory_id' => $order->factory_id
                ]);
            }

            DB::commit();
            return ['message' => "Event Dispatch Successful Style Wise"];
        } catch (Throwable $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ];
        }
    }

    public function dataAssignToReportTableForPOWise($poId): string
    {
        try {
            DB::beginTransaction();
            $po = PurchaseOrder::query()->find($poId);
            $po->append('lead_time', $this->leadTime($po));
            $template = $this->templateSelector->template($po);
            $this->previousDataRemover->deleteForPO($po);
            foreach ($template['details'] as $detail) {
                $data = $this->reportTableFormatter->formatForPO(['details' => $detail, 'po' => $po, 'template' => $template]);
                TNAReports::query()->create($data);
                TNANotificationService::notification($detail, [
                    'po_no' => $po->po_no,
                    'buyer_id' => $po->buyer_id,
                    'job_no' => $po->order->job_no,
                    'factory_id' => $po->factory_id
                ]);
            }
            DB::commit();
            return "Event Dispatch Successful PO Wise";
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * @throws Exception
     */
    public function leadTime($po): int
    {
        $receiveDate = Carbon::parse($po->po_receive_date);
        $deliveryDate = Carbon::parse($po->ex_factory_date);
        if (!$receiveDate || !$deliveryDate) {
            throw new Exception('Please Receive & Delivery Date must be entire first.');
        }
        return $receiveDate->diffInDays($deliveryDate, false);
    }

    public function variable($factoryId, $buyerId)
    {
        return MerchandisingVariableSettings::query()
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->first();
    }

    public function search($request)
    {
        $variable = $this->variable($request->get('factory_id'), $request->get('buyer_id'))->variables_details;
        $poId = $request->get('po_id');
        $orderId = $request->get('order_id');
        $shiftFromDate = $request->get('ship_from_date');
        $shiftToDate = $request->get('ship_to_date');
        $searchType = $request->get('search_type');
        $taskId = $request->get('task_id');
        $dealingMerchantId = $request->get('dealing_merchant_id');

        return TNAReports::query()
            ->with(['task.group', 'order.buyer', 'order.dealingMerchant', 'purchaseOrder'])
            ->where('factory_id', $request->get('factory_id'))
            ->where('buyer_id', $request->get('buyer_id'))
            ->when($orderId && !$poId, function ($q) use ($orderId) {
                $q->where('order_id', $orderId)
                    ->where('based_on', TNAReports::ORDER_WISE);
            })
            ->when($poId, function ($q) use ($poId) {
                $q->where('po_id', $poId)
                    ->where('based_on', TNAReports::PO_WISE);
            })
            ->when($taskId, function ($q) use ($taskId) {
                $q->whereIn('task_id', $taskId);
            })
            ->when($dealingMerchantId, function ($q) use ($dealingMerchantId) {
                $q->whereHas('order', function ($query) use ($dealingMerchantId) {
                    $query->where('dealing_merchant_id', $dealingMerchantId);
                });
            })
            ->when($searchType && $shiftFromDate && $shiftToDate,
                function ($q) use ($shiftFromDate, $shiftToDate, $searchType) {
                    if ($searchType == 1) {
                        $q->whereHas('order', function ($query) use ($shiftFromDate, $shiftToDate, $searchType) {
                            $query->where('po_received_date', '>=', $shiftFromDate);
                            $query->where('po_received_date', '<=', $shiftToDate);
                        });
                    } else {
                        $q->whereHas('order', function ($query) use ($shiftFromDate, $shiftToDate, $searchType) {
                            $query->where('shipment_date', '>=', $shiftFromDate);
                            $query->where('shipment_date', '<=', $shiftToDate);
                        });
                    }
                })
            ->get();



//        if (!$variable || collect($variable)->get('tna_maintain') != 1) {
//            $tnaReport->where('based_on', 2);
//        } else {
//            $tnaReport->where('based_on', 1);
    }
}
