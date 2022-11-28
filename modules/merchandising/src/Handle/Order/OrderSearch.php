<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 10:25 AM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Order;

use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class OrderSearch
{
    private $column_name;
    private $query_string;

    public function __construct($column_name, $query_string)
    {
        $this->column_name = $column_name;
        $this->query_string = $query_string;
    }

    public function get()
    {
        $column_name = $this->column_name;
        $query_string = $this->query_string;
        $query = Order::withoutGlobalScope('factoryId')->with('buyer', 'agent', 'team', 'dealing_merchants');
        switch ($column_name) {
            case "order_style_no":
                $query->where('order_style_no', 'LIKE', "%$query_string%");

                break;
            case "booking_no":
                $query->where('booking_no', 'LIKE', "%$query_string%");

                break;
            case "buyer":
                $query->whereHas('buyer', function ($q) use ($query_string) {
                    $q->where('name', 'LIKE', "$query_string%");
                });

                break;
            case "agent":
                $query->orWhereHas('agent', function ($q) use ($query_string) {
                    $q->where('buying_agent_name', 'LIKE', "%$query_string%");
                });

                break;
            case "dealing_merchant":
                $query->whereHas('dealing_merchants', function ($q) use ($query_string) {
                    $q->where('screen_name', 'LIKE', "%$query_string%");
                });

                break;
            case "buying_agent":
                $query->orWhereHas('agent', function ($q) use ($query_string) {
                    $q->where('buying_agent_name', 'LIKE', "%$query_string%");
                });

                break;
            case "team_lead":
                $query->whereHas('team', function ($q) use ($query_string) {
                    $q->where('team_name', 'LIKE', "%$query_string%");
                });

                break;
            case "order_qty":
                $query->where('total_quantity', $query_string);

                break;
            case "confirmation_date":
                $date = date('Y-m-d', strtotime($query_string));
                $query->where('order_confirmation_date', 'LIKE', "%$date%");

                break;

            case 'factory_name':
                $query->whereHas('factory', function ($q) use ($query_string) {
                    $q->where('factory_name', 'LIKE', "%$query_string%")
                       ->orWhere('factory_short_name', 'LIKE', "%$query_string%");
                });

                break;

            default:
                Session::flash('alert-danger', 'Please Select Column For Search');

                return redirect('order/list')->withInput();
        }

        return $query;
    }
}
