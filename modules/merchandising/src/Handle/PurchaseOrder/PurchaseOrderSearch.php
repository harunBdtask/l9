<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 2:21 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PurchaseOrderSearch
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $query_string = $this->request->query_string;
        $column_name = $this->request->column_name;
        $query = PurchaseOrder::with('buyer', 'order', 'order.dealing_merchants', 'agent');

        switch ($column_name) {
            case "po_no":
                $query->where('po_no', 'LIKE', "%$query_string%");

                break;
            case "print":
                $query->where('print', 'LIKE', "%$query_string%");

                break;
            case "embroidery":
                $query->where('embroidery', 'LIKE', "%$query_string%");

                break;
            case "order_style_no":
                $query->whereHas('order', function ($q) use ($query_string) {
                    $q->where('order_style_no', 'LIKE', "%$query_string%");
                });

                break;
            case "booking_no":
                $query->whereHas('order', function ($q) use ($query_string) {
                    $q->where('booking_no', 'LIKE', "$query_string%");
                });

                break;
            case "buyer":
                $query->whereHas('buyer', function ($q) use ($query_string) {
                    $q->where('name', 'LIKE', "$query_string%");
                });

                break;
            case "agent":
            case "buying_agent":
                $query->orWhereHas('agent', function ($q) use ($query_string) {
                    $q->where('buying_agent_name', 'LIKE', "%$query_string%");
                });

                break;
            case "dealing_merchant":
                $query->orWhereHas('order.dealing_merchants', function ($q) use ($query_string) {
                    $q->where('first_name', 'LIKE', "%$query_string%");
                    $q->orWhere('last_name', 'LIKE', "%$query_string%");
                    $q->orWhere('screen_name', 'LIKE', "%$query_string%");
                });

                break;
            case "team_lead":
                $query->whereHas('team', function ($q) use ($query_string) {
                    $q->where('team_name', 'LIKE', "%$query_string%");
                });

                break;
            case "po_qty":
                $query->where('po_quantity', $query_string);

                break;
            case "ex_factory_date":
                $date = date('Y-m-d', strtotime($query_string));
                $query->where('ex_factory_date', 'LIKE', "%$date%");

                break;
            case 'factory_name':
                $query->whereHas('factory', function ($q) use ($query_string) {
                    $q->where('factory_name', 'LIKE', "%$query_string%")
                        ->orWhere('factory_short_name', 'LIKE', "%$query_string%");
                });

                break;
            default:
                return false;
        }

        $data['purchase_order_list'] = $query->orderBy('id', 'desc')->paginate();

        return $data;
    }
}
