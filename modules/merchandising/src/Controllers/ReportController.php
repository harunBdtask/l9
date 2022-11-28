<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Exports\OrderConfirmationReport;
use SkylarkSoft\GoRMG\Merchandising\Exports\OrderRecapReport;
use SkylarkSoft\GoRMG\Merchandising\Exports\OrderRecapSummaryReport;
use SkylarkSoft\GoRMG\Merchandising\Exports\RecapReportExcel;
use SkylarkSoft\GoRMG\Merchandising\Exports\RecapSummaryReport;
use SkylarkSoft\GoRMG\Merchandising\Handle\FabricReport\DateWiseFabricReport;
use SkylarkSoft\GoRMG\Merchandising\Handle\FabricReport\MonthlyFabricReport;
use SkylarkSoft\GoRMG\Merchandising\Handle\RecapReport\OrderWiseRecapReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoWiseRecapReportTable;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class ReportController extends Controller
{
    public function getOrderConfirmation(Request $request)
    {
        $buyer_id = $request->buyer_id ?? null;
        $start_date = $request->start_date ?? null;
        $end_date = $request->end_date ?? null;

        $buyers = Buyer::all()->pluck('name', 'id');

        if ($start_date && $end_date) {
            $init_date = Carbon::parse($start_date);
            $last_date = Carbon::parse($end_date);
            $date_diff = $last_date->diffInDays($init_date);
            if ($date_diff > 186) {
                session()->flash('alert-danger', 'Please give six month date range!');
                return redirect()->back();
            }
        }

        $orders_list = $this->getOrderConfirmationData($buyer_id, $start_date, $end_date);
        return view('merchandising::order.report.order_confirmation_list', [
            'buyer_id' => $buyer_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'buyers' => $buyers,
            'orders_list' => $orders_list,
        ]);
    }

    private function getOrderConfirmationData($buyer_id = '', $start_date = '', $end_date = '')
    {
        $orderQuery = Order::query()
            ->select('id', 'buyer_id', 'buying_agent_id', 'dealing_merchant_id', 'style_name')
            ->with(['buyer:id,name', 'buyingAgent:id,buying_agent_name', 'dealingMerchant:id,email,first_name,last_name', 'purchaseOrders' => function ($query) use ($start_date, $end_date) {
                return $query->whereNotNull('po_receive_date')
                    ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                        return $query->whereDate('po_receive_date', '>=', $start_date)
                            ->whereDate('po_receive_date', '<=', $end_date);
                    })->
                    when(($start_date == '' || $end_date == ''), function ($query) {
                        $start_date = now()->subDays(186)->toDateString();
                        return $query->whereDate('po_receive_date', '>=', $start_date);
                    });
            }])
            ->when($buyer_id != '', function ($query) use ($buyer_id) {
                return $query->where('buyer_id', $buyer_id);
            })
            ->whereHas('purchaseOrders', function ($query) use ($start_date, $end_date) {
                return $query->whereNotNull('po_receive_date')
                    ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                        return $query->whereDate('po_receive_date', '>=', $start_date)
                            ->whereDate('po_receive_date', '<=', $end_date);
                    })->
                    when(($start_date == '' || $end_date == ''), function ($query) {
                        $start_date = now()->subDays(186)->toDateString();
                        return $query->whereDate('po_receive_date', '>=', $start_date);
                    });
            })
            ->get();
        $po = $orderQuery->map(function ($item) {
            return [
                'buyer_name' => $item->buyer->name ?? '',
                'style_name' => $item->style_name ?? '',
                'po_no' => $item->purchaseOrders ? collect($item->purchaseOrders)->pluck('po_no')->unique()->implode(', ') : '',
                'total_qty' => $item->purchaseOrders ? collect($item->purchaseOrders)->sum('po_quantity') : 0,
                'buying_agent' => $item->buyingAgent->buying_agent_name ?? '',
                'dealing_merchant' => $item->dealingMerchant->screen_name ?? '',
                'po_receive_date' => $item->purchaseOrders ? collect($item->purchaseOrders)->pluck('po_receive_date')->unique()->implode(', ') : '',
            ];
        })->paginate();
        return $po;
    }


    //search view
    public function getOrderSearch(Request $request)
    {
        $data['buyer_id'] = $request->buyer_id;
        $id = $data['buyer_id'];
        $data['start_date'] = date('Y-m-d', strtotime($request->start_date));
        $data['end_date'] = date('Y-m-d', strtotime($request->end_date));
        $data['buyers'] = Buyer::distinct()->orderBy('name')->get();
        $orderList = Order::with(['buyer', 'agent', 'team', 'dealing_merchants']);
        //only buyer
        if ($data['start_date'] == "1970-01-01" && $data['end_date'] == "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->orderBy('order_confirmation_date', 'desc')
                ->paginate()->withPath('order-search-list?buyer_id=' . $id . '&start_date=' . $data['start_date'] . '&end_date=' . $data['end_date']);
        } //only buyer and end date
        elseif ($data['end_date'] == "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->where('order_confirmation_date', '=', $data['start_date'])
                ->orderBy('order_confirmation_date', 'desc')
                ->paginate()->withPath('order-search-list?buyer_id=' . $id . '&start_date=' . $data['start_date'] . '&end_date=' . $data['end_date']);
        } //search with three value
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] != "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->whereBetween('order_confirmation_date', [$data['start_date'], $data['end_date']])
                ->orderBy('order_confirmation_date', 'desc')
                ->paginate()->withPath('order-search-list?buyer_id=' . $id . '&start_date=' . $data['start_date'] . '&end_date=' . $data['end_date']);
        } //end date and buyer
        elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] != "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->where('order_confirmation_date', '=', $data['end_date'])
                ->orderBy('order_confirmation_date', 'desc')
                ->paginate()->withPath('order-search-list?buyer_id=' . $id . '&start_date=' . $data['start_date'] . '&end_date=' . $data['end_date']);
        } //date range
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] != "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList
                ->whereBetween('order_confirmation_date', [$data['start_date'], $data['end_date']])
                ->orderBy('order_confirmation_date', 'desc')
                ->paginate()->withPath('order-search-list?buyer_id=' . $id . '&start_date=' . $data['start_date'] . '&end_date=' . $data['end_date']);
        } //only start date
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] == "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList->where('order_confirmation_date', '=', $data['start_date'])
                ->orderBy('order_confirmation_date', 'desc')->paginate()->withPath('order-search-list?buyer_id=' . $id . '&start_date=' . $data['start_date'] . '&end_date=' . $data['end_date']);
        } //only end date
        elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] != "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList->where('order_confirmation_date', '=', $data['end_date'])
                ->orderBy('order_confirmation_date', 'desc')->paginate()->withPath('order-search-list?buyer_id=' . $id . '&start_date=' . $data['start_date'] . '&end_date=' . $data['end_date']);
        } elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] == "1970-01-01" && $id == null) {
            $request->validate([
                "buyer_id" => "required",
                "start_date" => "required",
                "end_date" => "required",
            ]);
        }
        $request->flash();

        return view('merchandising::order.report.order_confirmation_list', $data);
    }

    //download pdf file

    public function getOrderSearchWisePDFConfirmationReport(Request $request)
    {
        $data['buyer_id'] = $request->buyer_id;
        $id = $data['buyer_id'];
        $data['start_date'] = date('Y-m-d', strtotime($request->start_date));
        $data['end_date'] = date('Y-m-d', strtotime($request->end_date));
        $data['buyers'] = Buyer::distinct()->orderBy('name')->get();
        $orderList = Order::with(['buyer', 'agent', 'team', 'dealing_merchants']);
        //only buyer
        if ($data['start_date'] == "1970-01-01" && $data['end_date'] == "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //only buyer and end date
        elseif ($data['end_date'] == "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->where('order_confirmation_date', '=', $data['start_date'])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //search with three value
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] != "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->whereBetween('order_confirmation_date', [$data['start_date'], $data['end_date']])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //end date and buyer
        elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] != "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->where('order_confirmation_date', '=', $data['end_date'])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //date range
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] != "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList
                ->whereBetween('order_confirmation_date', [$data['start_date'], $data['end_date']])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //only start date
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] == "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList->where('order_confirmation_date', '=', $data['start_date'])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //only end date
        elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] != "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList->where('order_confirmation_date', '=', $data['end_date'])
                ->orderBy('order_confirmation_date', 'desc')->get();
        } //nothing selected
        elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] == "1970-01-01" && $id == null) {
            $request->validate([
                "buyer_id" => "required",
                "start_date" => "required",
                "end_date" => "required",
            ]);
        }
        $pdf = PDF::loadView('merchandising::order.report.pdf.order_confirmation_list_pdf', $data);

        return $pdf->download('order_confirmation_list.pdf');
    }

    public function getOrderSearchWiseExcelConfirmationReport(Request $request)
    {
        $data['buyer_id'] = $request->buyer_id;
        $id = $data['buyer_id'];
        $data['start_date'] = date('Y-m-d', strtotime($request->start_date));
        $data['end_date'] = date('Y-m-d', strtotime($request->end_date));
        $data['buyers'] = Buyer::distinct()->orderBy('name')->get();
        $orderList = Order::with(['buyer', 'agent', 'team', 'dealing_merchants']);
        //only buyer
        if ($data['start_date'] == "1970-01-01" && $data['end_date'] == "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //only buyer and end date
        elseif ($data['end_date'] == "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->where('order_confirmation_date', '=', $data['start_date'])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //search with three value
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] != "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->whereBetween('order_confirmation_date', [$data['start_date'], $data['end_date']])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //end date and buyer
        elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] != "1970-01-01" && $id != null) {
            $data['orders_list'] = $orderList->where('buyer_id', '=', $id)
                ->where('order_confirmation_date', '=', $data['end_date'])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //date range
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] != "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList
                ->whereBetween('order_confirmation_date', [$data['start_date'], $data['end_date']])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //only start date
        elseif ($data['start_date'] != "1970-01-01" && $data['end_date'] == "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList->where('order_confirmation_date', '=', $data['start_date'])
                ->orderBy('order_confirmation_date', 'desc')
                ->get();
        } //only end date
        elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] != "1970-01-01" && $id == null) {
            $data['orders_list'] = $orderList->where('order_confirmation_date', '=', $data['end_date'])
                ->orderBy('order_confirmation_date', 'desc')->get();
        } //nothing selected
        elseif ($data['start_date'] == "1970-01-01" && $data['end_date'] == "1970-01-01" && $id == null) {
            $request->validate([
                "buyer_id" => "required",
                "start_date" => "required",
                "end_date" => "required",
            ]);
        }

        return Excel::download(new OrderConfirmationReport($data), 'order_confirmation_list_report.xlsx');
    }

    public function recapReport()
    {
        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');

        $data['recap'] = null;

        return view('merchandising::recap.recap_report', $data);
    }

    public function recapSearch(Request $request)
    {
        $buyer_id = $request->input('buyer_id') ?? null;
        $month = $request->input('month') ?? date('n', time());
        $year = $request->input('year') ?? date('Y', time());

        $po_relations = [
            'order', 'order.items', 'po_details', 'po_details.item',
        ];
        $pos = PurchaseOrder::with($po_relations);
        if ($buyer_id) {
            $pos->where('buyer_id', $buyer_id);
        }
        $pos->whereMonth('ex_factory_date', $month);
        $pos->whereYear('ex_factory_date', $year);
        $pos->orderBy('buyer_id', 'asc');
        $pos->orderBy('order_id', 'desc');

        $pos_result = $pos->get();

        foreach ($pos_result as $index => $po) {
            $items = [];
            $categories = [];
            $fabrication = [];
            $fabric_special = [];
            $gsm = [];
            $unit_prices = [];
            foreach ($po->po_details as $detail) {
                $items[] = $detail->item->item_name;
                $item_categories = $po->order->items->where('item_id', $detail->item_id)->pluck('item_category');
                $item_fabrications = $po->order->items->where('item_id', $detail->item_id)->pluck('fabrication');
                $item_gsms = $po->order->items->where('item_id', $detail->item_id)->pluck('gsm');
                $item_unit_prices = $po->order->items->where('item_id', $detail->item_id)->pluck('unit_price');
                $fabric_special = collect(
                    array_filter($po->po_details->pluck('color_type')->unique()->toArray(), function ($item) {
                        return $item != 1;
                    })
                )
                    ->map(function ($item) {
                        return ($item == 3) ? "AOP" : "YD";
                    });

                foreach ($item_gsms as $item_gsm) {
                    $gsm[] = $item_gsm;
                }

                foreach ($item_categories as $item_category) {
                    $category_name = ITEM_CATEGORY[$item_category] ?? 'Others';
                    $categories[$category_name] = $detail->where('purchase_order_id', $po->id)
                        ->where('item_id', $detail->item_id)
                        ->sum('quantity');
                }

                foreach ($item_fabrications as $item_fabrication) {
                    $fabrication[] = $item_fabrication;
                }

                foreach ($item_gsms as $item_gsm) {
                    $gsm[] = $item_gsm;
                }

                foreach ($item_unit_prices as $item_unit_price) {
                    $unit_prices[$detail->item_id] = $item_unit_price;
                }
            }

            $pos_result[$index]->setAttribute('po_items', collect($items)->unique());
            $pos_result[$index]->setAttribute('item_fabrication', collect($fabrication)->unique());
            $pos_result[$index]->setAttribute('fab_special', $fabric_special);
            $pos_result[$index]->setAttribute('item_gsm', collect($gsm)->unique());
            $pos_result[$index]->setAttribute('unit_price', collect($unit_prices)->sum());
            $pos_result[$index]->setAttribute('total_value', collect($unit_prices)->sum() * $po->po_quantity);

            foreach ($categories as $key => $category) {
                $pos_result[$index]->setAttribute($key, $category);
            }
        }

        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $data['pos'] = $pos_result;
        return view('merchandising::recap.recap_report_new', $data);
    }

    public function recapReportPrint(Request $request)
    {
        $buyer_id = $request->input('buyer_id') ?? null;
        $month = $request->input('month') ?? date('n', time());
        $year = $request->input('year') ?? date('Y', time());

        $po_relations = [
            'order', 'order.items', 'po_details', 'po_details.item',
        ];
        $pos = PurchaseOrder::with($po_relations);
        if ($buyer_id) {
            $pos->where('buyer_id', $buyer_id);
        }
        $pos->whereMonth('ex_factory_date', $month);
        $pos->whereYear('ex_factory_date', $year);
        $pos->orderBy('buyer_id', 'asc');
        $pos->orderBy('order_id', 'desc');

        $pos_result = $pos->get();

        foreach ($pos_result as $index => $po) {
            $items = [];
            $categories = [];
            $fabrication = [];
            $fabric_special = [];
            $gsm = [];
            $unit_prices = [];
            foreach ($po->po_details as $detail) {
                $items[] = $detail->item->item_name;
                $item_categories = $po->order->items->where('item_id', $detail->item_id)->pluck('item_category');
                $item_fabrications = $po->order->items->where('item_id', $detail->item_id)->pluck('fabrication');
                $item_gsms = $po->order->items->where('item_id', $detail->item_id)->pluck('gsm');
                $item_unit_prices = $po->order->items->where('item_id', $detail->item_id)->pluck('unit_price');
                $fabric_special = collect(
                    array_filter($po->po_details->pluck('color_type')->unique()->toArray(), function ($item) {
                        return $item != 1;
                    })
                )
                    ->map(function ($item) {
                        return ($item == 3) ? "AOP" : "YD";
                    });

                foreach ($item_gsms as $item_gsm) {
                    $gsm[] = $item_gsm;
                }

                foreach ($item_categories as $item_category) {
                    $category_name = ITEM_CATEGORY[$item_category] ?? 'Others';
                    $categories[$category_name] = $detail->where('purchase_order_id', $po->id)
                        ->where('item_id', $detail->item_id)
                        ->sum('quantity');
                }

                foreach ($item_fabrications as $item_fabrication) {
                    $fabrication[] = $item_fabrication;
                }

                foreach ($item_gsms as $item_gsm) {
                    $gsm[] = $item_gsm;
                }

                foreach ($item_unit_prices as $item_unit_price) {
                    $unit_prices[$detail->item_id] = $item_unit_price;
                }
            }

            $pos_result[$index]->setAttribute('po_items', collect($items)->unique());
            $pos_result[$index]->setAttribute('item_fabrication', collect($fabrication)->unique());
            $pos_result[$index]->setAttribute('fab_special', $fabric_special);
            $pos_result[$index]->setAttribute('item_gsm', collect($gsm)->unique());
            $pos_result[$index]->setAttribute('unit_price', collect($unit_prices)->sum());
            $pos_result[$index]->setAttribute('total_value', collect($unit_prices)->sum() * $po->po_quantity);

            foreach ($categories as $key => $category) {
                $pos_result[$index]->setAttribute($key, $category);
            }
        }

        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $data['pos'] = $pos_result;

        return view('merchandising::recap.recap_report_print_new', $data);
    }

    public function recapReportPdfDownload(Request $request)
    {
        set_time_limit(0);
        $buyer_id = $request->input('buyer_id') ?? null;
        $month = $request->input('month') ?? date('n', time());
        $year = $request->input('year') ?? date('Y', time());

        $po_relations = [
            'order', 'order.items', 'po_details', 'po_details.item',
        ];
        $pos = PurchaseOrder::with($po_relations);
        if ($buyer_id) {
            $pos->where('buyer_id', $buyer_id);
        }
        $pos->whereMonth('ex_factory_date', $month);
        $pos->whereYear('ex_factory_date', $year);
        $pos->orderBy('buyer_id', 'asc');
        $pos->orderBy('order_id', 'desc');

        $pos_result = $pos->get();

        foreach ($pos_result as $index => $po) {
            $items = [];
            $categories = [];
            $fabrication = [];
            $fabric_special = [];
            $gsm = [];
            $unit_prices = [];
            foreach ($po->po_details as $detail) {
                $items[] = $detail->item->item_name;
                $item_categories = $po->order->items->where('item_id', $detail->item_id)->pluck('item_category');
                $item_fabrications = $po->order->items->where('item_id', $detail->item_id)->pluck('fabrication');
                $item_gsms = $po->order->items->where('item_id', $detail->item_id)->pluck('gsm');
                $item_unit_prices = $po->order->items->where('item_id', $detail->item_id)->pluck('unit_price');
                $fabric_special = collect(
                    array_filter($po->po_details->pluck('color_type')->unique()->toArray(), function ($item) {
                        return $item != 1;
                    })
                )
                    ->map(function ($item) {
                        return ($item == 3) ? "AOP" : "YD";
                    });

                foreach ($item_gsms as $item_gsm) {
                    $gsm[] = $item_gsm;
                }

                foreach ($item_categories as $item_category) {
                    $category_name = ITEM_CATEGORY[$item_category] ?? 'Others';
                    $categories[$category_name] = $detail->where('purchase_order_id', $po->id)
                        ->where('item_id', $detail->item_id)
                        ->sum('quantity');
                }

                foreach ($item_fabrications as $item_fabrication) {
                    $fabrication[] = $item_fabrication;
                }

                foreach ($item_gsms as $item_gsm) {
                    $gsm[] = $item_gsm;
                }

                foreach ($item_unit_prices as $item_unit_price) {
                    $unit_prices[$detail->item_id] = $item_unit_price;
                }
            }

            $pos_result[$index]->setAttribute('po_items', collect($items)->unique());
            $pos_result[$index]->setAttribute('item_fabrication', collect($fabrication)->unique());
            $pos_result[$index]->setAttribute('fab_special', $fabric_special);
            $pos_result[$index]->setAttribute('item_gsm', collect($gsm)->unique());
            $pos_result[$index]->setAttribute('unit_price', collect($unit_prices)->sum());
            $pos_result[$index]->setAttribute('total_value', collect($unit_prices)->sum() * $po->po_quantity);

            foreach ($categories as $key => $category) {
                $pos_result[$index]->setAttribute($key, $category);
            }
        }

        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $data['pos'] = $pos_result;
        $pdf = PDF::loadView('merchandising::recap.recap_report_excel_new', $data)->setPaper('a4', 'landscape');

        return $pdf->download('recap_' . date('d_m_Y') . '.pdf');
    }

    public function recapReportExcelDownload(Request $request)
    {
        $buyer_id = $request->input('buyer_id') ?? null;
        $month = $request->input('month') ?? date('n', time());
        $year = $request->input('year') ?? date('Y', time());

        $po_relations = [
            'order', 'order.items', 'po_details', 'po_details.item',
        ];
        $pos = PurchaseOrder::with($po_relations);
        if ($buyer_id) {
            $pos->where('buyer_id', $buyer_id);
        }
        $pos->whereMonth('ex_factory_date', $month);
        $pos->whereYear('ex_factory_date', $year);
        $pos->orderBy('buyer_id', 'asc');
        $pos->orderBy('order_id', 'desc');

        $pos_result = $pos->get();

        foreach ($pos_result as $index => $po) {
            $items = [];
            $categories = [];
            $fabrication = [];
            $fabric_special = [];
            $gsm = [];
            $unit_prices = [];
            foreach ($po->po_details as $detail) {
                $items[] = $detail->item->item_name;
                $item_categories = $po->order->items->where('item_id', $detail->item_id)->pluck('item_category');
                $item_fabrications = $po->order->items->where('item_id', $detail->item_id)->pluck('fabrication');
                $item_gsms = $po->order->items->where('item_id', $detail->item_id)->pluck('gsm');
                $item_unit_prices = $po->order->items->where('item_id', $detail->item_id)->pluck('unit_price');
                $fabric_special = collect(
                    array_filter($po->po_details->pluck('color_type')->unique()->toArray(), function ($item) {
                        return $item != 1;
                    })
                )
                    ->map(function ($item) {
                        return ($item == 3) ? "AOP" : "YD";
                    });

                foreach ($item_gsms as $item_gsm) {
                    $gsm[] = $item_gsm;
                }

                foreach ($item_categories as $item_category) {
                    $category_name = ITEM_CATEGORY[$item_category] ?? 'Others';
                    $categories[$category_name] = $detail->where('purchase_order_id', $po->id)
                        ->where('item_id', $detail->item_id)
                        ->sum('quantity');
                }

                foreach ($item_fabrications as $item_fabrication) {
                    $fabrication[] = $item_fabrication;
                }

                foreach ($item_gsms as $item_gsm) {
                    $gsm[] = $item_gsm;
                }

                foreach ($item_unit_prices as $item_unit_price) {
                    $unit_prices[$detail->item_id] = $item_unit_price;
                }
            }

            $pos_result[$index]->setAttribute('po_items', collect($items)->unique());
            $pos_result[$index]->setAttribute('item_fabrication', collect($fabrication)->unique());
            $pos_result[$index]->setAttribute('fab_special', $fabric_special);
            $pos_result[$index]->setAttribute('item_gsm', collect($gsm)->unique());
            $pos_result[$index]->setAttribute('unit_price', collect($unit_prices)->sum());
            $pos_result[$index]->setAttribute('total_value', collect($unit_prices)->sum() * $po->po_quantity);

            foreach ($categories as $key => $category) {
                $pos_result[$index]->setAttribute($key, $category);
            }
        }

        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $data['pos'] = $pos_result;

        return Excel::download(new RecapReportExcel($data), 'recap.xlsx');
    }

    public function recapSummaryReportNew(Request $request)
    {
        $buyer_id = $request->input('buyer_id') ?? null;
        $month = $request->input('month') ?? date('n', time());
        $year = $request->input('year') ?? date('Y', time());

        $po_relations = [
            'order', 'order.items', 'po_details', 'po_details.item',
        ];
        $pos = PurchaseOrder::with($po_relations);
        if ($buyer_id) {
            $pos->where('buyer_id', $buyer_id);
        }
        $pos->whereMonth('ex_factory_date', $month);
        $pos->whereYear('ex_factory_date', $year);
        $pos->orderBy('buyer_id', 'asc');
        $pos->orderBy('order_id', 'desc');

        $pos_result = $pos->get();

        foreach ($pos_result as $index => $po) {
            $items = [];
            $categories = [];
            $unit_prices = [];
            foreach ($po->po_details as $detail) {
                $item_categories = $po->order->items->where('item_id', $detail->item_id)->pluck('item_category');
                $item_unit_prices = $po->order->items->where('item_id', $detail->item_id)->pluck('unit_price');

                foreach ($item_categories as $item_category) {
                    $category_name = ITEM_CATEGORY[$item_category] ?? 'Others';
                    $categories[$category_name] = $detail->where('purchase_order_id', $po->id)
                        ->where('item_id', $detail->item_id)
                        ->sum('quantity');
                }

                foreach ($item_unit_prices as $item_unit_price) {
                    $unit_prices[$detail->item_id] = $item_unit_price;
                }
            }

            $pos_result[$index]->setAttribute('po_items', collect($items)->unique());
            $pos_result[$index]->setAttribute('unit_price', collect($unit_prices)->sum());
            $pos_result[$index]->setAttribute('total_value', collect($unit_prices)->sum() * $po->po_quantity);
            $pos_result[$index]->setAttribute('total_value', collect($unit_prices)->sum() * $po->po_quantity);

            foreach ($categories as $key => $category) {
                $pos_result[$index]->setAttribute($key, $category);
            }
        }

        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $data['pos'] = $pos_result;

        return view('merchandising::recap.recap_summary_report_new', $data);
    }

    public function recapSummaryReport(Request $request)
    {
        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $buyer = $request->buyer_id;
        $month = $request->month;
        $year = $request->year;
        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');

        $arra = [];
        $sum = 0;
        $orders = Order::with('purchase_orders', 'purchase_orders.po_details.item');
        if (isset($buyer)) {
            $orders->where('buyer_id', $buyer);
        }
        $orders->whereHas('purchase_orders', function ($orders) use ($month) {
            $orders->whereMonth('ex_factory_date', $month);
        });
        $orders->whereHas('purchase_orders', function ($orders) use ($year) {
            $orders->whereYear('ex_factory_date', $year);
        });
        $orders = $orders->orderBy('buyer_id', 'asc')->get();
        $orders->map(function ($order) use (&$arra, $sum) {
            foreach ($order->purchase_orders as $po) {
                foreach ($po->po_details as $po_details) {
                    $datas['buyer_id'] = $order->buyer_id;
                    $datas['buyer_name'] = $order->buyer->name;
                    $datas['order_id'] = $order->id;
                    $datas['order_style_no'] = $order->order_style_no;
                    $datas['booking_no'] = $order->booking_no;
                    $datas['po_id'] = $po->id;
                    $datas['po_no'] = $po->po_no;
                    $datas['item_id'] = $po_details->item_id;
                    $datas['item_name'] = $po_details->item->item_name ?? '';
                    $datas['item_fabrication'] = $po_details->fabrication ?? '';
                    $datas['item_quantity'] = $po_details->quantity ?? 0;
                    $datas['item_gsm'] = $po_details->gsm ?? 0;
                    $datas['dealing_merchant'] = $order->dealing_merchants->screen_name ?? '--';
                    $datas['item_unit_price'] = $order->items->where('item_id', $po_details->item_id)->first()->unit_price ?? 0;
                    $datas['shipment_date'] = $po->ex_factory_date;
                    $datas['total_sum'] = ($datas['item_unit_price'] * $datas['item_quantity']);
                    $arra[] = (object)$datas;
                }
            }

            return $arra;
        });

        $data['recap'] = collect($arra)->groupBy(['buyer_id', 'item_id']);

        return view('merchandising::recap.recap_summary_report', $data);
    }

    public function recapSummaryReportDownload(Request $request)
    {
        $buyer_id = $request->input('buyer_id') ?? null;
        $month = $request->input('month') ?? date('n', time());
        $year = $request->input('year') ?? date('Y', time());

        $po_relations = [
            'order', 'order.items', 'po_details', 'po_details.item',
        ];
        $pos = PurchaseOrder::with($po_relations);
        if ($buyer_id) {
            $pos->where('buyer_id', $buyer_id);
        }
        $pos->whereMonth('ex_factory_date', $month);
        $pos->whereYear('ex_factory_date', $year);
        $pos->orderBy('buyer_id', 'asc');
        $pos->orderBy('order_id', 'desc');

        $pos_result = $pos->get();

        foreach ($pos_result as $index => $po) {
            $items = [];
            $categories = [];
            $unit_prices = [];
            foreach ($po->po_details as $detail) {
                $item_categories = $po->order->items->where('item_id', $detail->item_id)->pluck('item_category');
                $item_unit_prices = $po->order->items->where('item_id', $detail->item_id)->pluck('unit_price');

                foreach ($item_categories as $item_category) {
                    $category_name = ITEM_CATEGORY[$item_category] ?? 'Others';
                    $categories[$category_name] = $detail->where('purchase_order_id', $po->id)
                        ->where('item_id', $detail->item_id)
                        ->sum('quantity');
                }

                foreach ($item_unit_prices as $item_unit_price) {
                    $unit_prices[$detail->item_id] = $item_unit_price;
                }
            }

            $pos_result[$index]->setAttribute('po_items', collect($items)->unique());
            $pos_result[$index]->setAttribute('unit_price', collect($unit_prices)->sum());
            $pos_result[$index]->setAttribute('total_value', collect($unit_prices)->sum() * $po->po_quantity);
            $pos_result[$index]->setAttribute('total_value', collect($unit_prices)->sum() * $po->po_quantity);

            foreach ($categories as $key => $category) {
                $pos_result[$index]->setAttribute($key, $category);
            }
        }

        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $data['pos'] = $pos_result;
        if (request()->segment(1) == 'recap-summary-report-pdf-download') {
            $pdf = PDF::loadView('merchandising::recap.recap_summary_report_excel', $data)->setPaper('a4', 'landscape');

            return $pdf->download('recap_' . date('d_m_Y') . '.pdf');
        } else {
            return Excel::download(new RecapSummaryReport($data), 'recap-summary.xlsx');
        }
    }

    public function dateWiseFabricReport()
    {
        $data['report_data'] = null;

        return view('merchandising::fabric-report.fabric_report', $data);
    }

    public function dateWiseFabricReportGenerate(Request $request)
    {
        $data['report_data'] = (new DateWiseFabricReport($request))->generate();

        return view('merchandising::fabric-report.fabric_report', $data);
    }

    public function dateWiseFabricReportPdfDownload(Request $request)
    {
        $data['report_data'] = (new DateWiseFabricReport($request))->generate();
        $pdf = PDF::loadView('merchandising::fabric-report.fabric_report_pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download('fabric_report_status.pdf');
    }

    public function monthlyFabricSummaryReport()
    {
        $data['report_data'] = null;

        return view('merchandising::fabric-report.monthly_fabric_summary_report', $data);
    }

    public function monthlyFabricReportSummary(Request $request)
    {
        $data['report_data'] = (new MonthlyFabricReport($request))->generate();

        return view('merchandising::fabric-report.monthly_fabric_summary_report', $data);
    }

    public function monthlyFabricReportSummaryPdfDownload(Request $request)
    {
        $data['report_data'] = (new MonthlyFabricReport($request))->generate();
        $pdf = PDF::loadView('merchandising::fabric-report.fabric_report_pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download('monthly_fabric_report_status.pdf');
    }

    public function orderRecapReport()
    {
        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');

        return view('merchandising::recap.order_recap_report', $data);
    }

    public function OrderRecapReportSearch(Request $request)
    {
        $data = (new OrderWiseRecapReport($request))->getReportData();

        return view('merchandising::recap.order_recap_report', $data);
    }

    public function orderRecapReportPdfDownload(Request $request)
    {
        $data = (new OrderWiseRecapReport($request))->getReportData();
        $data['month'] = $request->month . '-' . $request->year;
        $pdf = PDF::loadView('merchandising::recap.order_wise_recap_report', $data)->setPaper('a4', 'landscape');

        return $pdf->download('order_recap_' . date('d_m_Y') . '.pdf');
    }

    public function orderRecapReportExcelDownload(Request $request)
    {
        $data = (new OrderWiseRecapReport($request))->getReportData();

        return Excel::download(new OrderRecapReport($data), 'order-recap-report.xlsx');
    }

    public function orderRecapSummaryReport()
    {
        $data['buyers'] = Buyer::pluck('name', 'id');
        $buyer_id = \request('buyer_id') ?? null;
        $month = request('month') ?? \Carbon\Carbon::now()->month;
        $year = request('year') ?? \Carbon\Carbon::now()->year;
        $data['orders'] = $this->getDataOrderRecapSummary($buyer_id, $month, $year);

        return view('merchandising::recap.order_recap_summary_report', $data);
    }

    public function orderRecapSummaryReportPdfDownload()
    {
        $data['buyers'] = Buyer::pluck('name', 'id');
        $buyer_id = \request('buyer_id') ?? null;
        $month = request('month') ?? \Carbon\Carbon::now()->month;
        $year = request('year') ?? \Carbon\Carbon::now()->year;
        $data['orders'] = $this->getDataOrderRecapSummary($buyer_id, $month, $year);
        $pdf = PDF::loadView('merchandising::recap.order_recap_summary_report_pdf', $data);

        return $pdf->download('order_recap_summary_report_' . date('d_m_Y') . '.pdf');
    }

    public function orderRecapSummaryReportExcelDownload()
    {
        $data['buyers'] = Buyer::pluck('name', 'id');
        $buyer_id = \request('buyer_id') ?? null;
        $month = request('month') ?? \Carbon\Carbon::now()->month;
        $year = request('year') ?? \Carbon\Carbon::now()->year;


        $data['orders'] = $this->getDataOrderRecapSummary($buyer_id, $month, $year);


        return Excel::download(new OrderRecapSummaryReport($data), 'order_recap_summary_report.xlsx');
    }

    public function getDataOrderRecapSummary($buyer_id, $month, $year)
    {
        $buyers = Buyer::with(['orderItems' => function ($query) use ($year, $month) {
            return $query->whereYear('orders.order_shipment_date', $year)->whereMonth('orders.order_shipment_date', $month);
        }]);


        if ($buyer_id) {
            $buyers->where('id', $buyer_id);
        }

        $orders = $buyers->get()->filter(function ($value) {
            return count($value->orderItems);
        });
        $newOrders = [];
        foreach ($orders as $key => $order) {
            $totalTShirt = 0;
            $totalPolo = 0;
            $totalPant = 0;
            $totalIntimates = 0;
            $totalOther = 0;
            $totalValue = 0;
            foreach ($order->orderItems as $item) {
                $totalValue += $item->unit_price * $item->quantity;
                $newOrders[$order->name]["6"] = $totalValue;

                if ($item->item_category == 1) {
                    $totalTShirt += $item->quantity;
                    $newOrders[$order->name][$item->item_category] = $totalTShirt;
                }
                if ($item->item_category == 2) {
                    $totalPolo += $item->quantity;
                    $newOrders[$order->name][$item->item_category] = $totalPolo;
                }
                if ($item->item_category == 3) {
                    $totalPant += $item->quantity;
                    $newOrders[$order->name][$item->item_category] = $totalPant;
                }
                if ($item->item_category == 4) {
                    $totalIntimates += $item->quantity;
                    $newOrders[$order->name][$item->item_category] = $totalIntimates;
                }
                if ($item->item_category == 5) {
                    $totalOther += $item->quantity;
                    $newOrders[$order->name][$item->item_category] = $totalOther;
                }
            }
        }

        return $newOrders;
    }

    public function poWiseRecapReport(Request $request)
    {
        $data['recap_report'] = [];
        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');

        return view('merchandising::reports.po_wise_recap_report', $data);
    }

    public function poWiseRecapReportSearch(Request $request)
    {
        $buyer_id = $request->buyer_id;
        $month = $request->month;
        $year = $request->year;

        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $query = PoWiseRecapReportTable::with('buyers', 'order', 'purchase', 'item_data');
        if ($buyer_id) {
            $query->where('buyer', $buyer_id);
        }
        if ($month) {
            $query->whereMonth('shipment_date', $month);
        }
        if ($year) {
            $query->whereYear('shipment_date', $year);
        }
        $query->orderBy('buyer', 'asc');
        $query->orderBy('order_id', 'desc');

        $data['recap_report'] = $query->get();

        return view('merchandising::reports.po_wise_recap_report', $data);
    }

    public function poWiseRecapReportPdf(Request $request)
    {
        $buyer_id = $request->buyer_id;
        $month = $request->month;
        $year = $request->year;

        $data['buyers'] = Buyer::pluck('name', 'id')->prepend('Select Buyer', '');
        $query = PoWiseRecapReportTable::with('buyers', 'order', 'purchase', 'item_data');
        if ($buyer_id) {
            $query->where('buyer', $buyer_id);
        }
        if ($month) {
            $query->whereMonth('shipment_date', $month);
        }
        if ($year) {
            $query->whereYear('shipment_date', $year);
        }
        $query->orderBy('buyer', 'asc');
        $query->orderBy('order_id', 'desc');
        $data['recap_report'] = $query->get();
        $pdf = PDF::loadView('merchandising::reports.po_wise_recap_report_pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download('recap_' . date('d_m_Y') . '.pdf');
    }
}
