<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/24/19
 * Time: 3:22 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Handle\Order\Interfaces\OrderInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class OrderMasterStore implements OrderInterface
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $id = $this->request->order_id ?? null;

        try {
            DB::beginTransaction();
            $order = Order::findOrNew($id);
            $order->sample_id = $this->request->sample_id;
            $order->repeat_order = $this->request->repeat_order ?? $this->request->order_style_no;
            $order->repeat_no = $this->request->repeat_no;
            $order->buyer_id = $this->request->buyer_id;
            $order->agent_id = $this->request->agent_id;
            $order->order_style_no = $this->request->order_style_no;
            $order->team_leader = $this->request->team_leader;
            $order->dealing_merchant = $this->request->dealing_merchant;
            $order->order_confirmation_date = date('Y-m-d', strtotime($this->request->order_confirmation_date));
            $order->order_shipment_date = date('Y-m-d', strtotime($this->request->order_shipment_date));
            $order->season = $this->request->season;
            $order->currency_id = $this->request->currency_id;
            $order->total_quantity = $this->request->total_quantity;
            $order->total_po = $this->request->total_po;
            $order->product_department_id = $this->request->product_department_id;
            $order->remarks = $this->request->update_remark;
            $order->excess_cutting_percent = $this->request->excess_cutting_percent ?? 0;
            $order->booking_no = $id ? $this->request->booking_no : $this->request->booking_no . '/' . date('Y');
            $order->manager = $this->request->manager;

            if ($this->request->hasFile('order_files')) {
                $time = time();
                $file = $this->request->order_files;
                $file->storeAs('order_files', $time . $file->getClientOriginalName());
                $order->order_files = $time . $file->getClientOriginalName();
            }
            $order->save();

            // If this is update then delete order details item
            if (isset($id)) {
                $additional_task = [
                    OrderItemDetailDelete::class,
                    OrderColorBreakdownDelete::class,
                    OrderSizeBreakdownDelete::class,
                ];
                foreach ($additional_task as $task) {
                    $response = (new $task($this->request->order_id))->handle();
                    if ($response == 501) {
                        return $response;
                    }
                }
            }

            $insertChildTable = [
                OrderItemDetailsStore::class,
                OrderColorBreakdownStore::class,
                OrderSizeBreakdownStore::class,
            ];
            foreach ($insertChildTable as $task) {
                (new $task($this->request, $order))->handle();
            }

            DB::commit();

            return 200;
        } catch (Exception $e) {
            DB::rollback();

            return false;
        }
    }
}
