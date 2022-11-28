<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\LcRequest;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Commercial\Models\LcRequest\LCRequest;
use SkylarkSoft\GoRMG\Commercial\Models\LcRequest\LCRequestDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Commercial\Exports\LCRequestExport;

class LcRequestController extends Controller
{
    public function index()
    {
        $search = request('search');
        $lcRequests = LCRequest::with('buyer:id,name')
            ->when($search, function ($query) use ($search) {
                $s = '%' . $search . '%';
                return $query->where('unique_id', 'LIKE', $s)
                    ->orWhere('attention', 'LIKE', $s)
                    ->orWhereHas('buyer', function ($q) use ($s) {
                        return $q->where('name', 'LIKE', $s);
                    })
                    ->orWhere('request_date', 'LIKE', $s)
                    ->orWhere('open_date', 'LIKE', $s);
            })
            ->orderByDesc('id')->get()->paginate();
        return view('commercial::lc-request.index', compact('lcRequests'));
    }

    public function create()
    {
        return view('commercial::lc-request.create');
    }

    public function show(LCRequest $LCRequest)
    {
        return response()->json($LCRequest->load('details'), Response::HTTP_ACCEPTED);
    }

    public function store(Request $request)
    {
        try {
            $lcRequest = new LCRequest($request->all());
            $lcRequest->save();
            return response()->json($lcRequest, Response::HTTP_ACCEPTED);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, LCRequest $LCRequest)
    {
        try {
            $lcRequest = $LCRequest->fill($request->all());
            $lcRequest->save();
            return response()->json($lcRequest, Response::HTTP_ACCEPTED);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(LCRequest $LCRequest, Request $request)
    {
        try {
            DB::beginTransaction();
            $LCRequest->delete();
            $LCRequest->details()->delete();
            DB::commit();
            if ($request->ajax()) {
                return response()->json('successfully delete', Response::HTTP_CREATED);
            }
            Session::flash('success', 'Data Deleted successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
            Session::flash('error', "Something went wrong!{$e->getMessage()}");
        }

        return redirect()->back();
    }

    public function getStyle($buyerId, $factoryId)
    {
        $style = Order::query()->where(['factory_id' => $factoryId, 'buyer_id' => $buyerId])
            ->get()->pluck('style_name')->unique()->values();
        $style = collect($style)->map(function ($item) {
            return [
                'id' => $item,
                'text' => $item,
            ];
        });
        return response()->json($style);
    }

    public function getPo($buyerId, $factoryId, $styleName)
    {
        $po = PurchaseOrder::query()
            ->whereHas('order', function ($query) use ($factoryId, $buyerId, $styleName) {
                return $query->where(['buyer_id' => $buyerId, 'factory_id' => $factoryId, 'style_name' => $styleName]);
            })->get()->pluck('po_no')->map(function ($item) {
                return [
                    'id' => $item,
                    'text' => $item
                ];
            });
        return response()->json($po);
    }

    public function getOrders(Request $request)
    {
        $factoryId = $request->factory_id;
        $buyerId = $request->buyer_id;
        $styleName = $request->style_name ?? null;
        $po = $request->po_no ?? null;
        $type = $request->search_type ?? null;
        $fromDate = $request->from_date ?? null;
        $toDate = $request->to_date ?? null;

        $lcRequests = LCRequestDetails::query()->get()->pluck('purchase_order_id')->toArray();

        $orders = PurchaseOrder::query()
            ->whereHas('order', function ($query) use ($factoryId, $buyerId, $styleName) {
                return $query->when($styleName, function ($q) use ($factoryId, $buyerId, $styleName) {
                    return $q->where(['buyer_id' => $buyerId, 'factory_id' => $factoryId, 'style_name' => $styleName]);
                })->when(!$styleName, function ($q) use ($factoryId, $buyerId) {
                    return $q->where(['buyer_id' => $buyerId, 'factory_id' => $factoryId]);
                });
            })
            ->when($po, function ($query) use ($po) {
                return $query->where('po_no', $po);
            })
            ->when($type == "original_shipment_Date", function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('ex_factory_date', [$fromDate, $toDate]);
            })
            ->when($type == "factory_ship_date", function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('country_ship_date', [$fromDate, $toDate]);
            })
            ->with(['order:id,ship_mode,style_name', 'poDetails.garmentItem'])
            ->get();

        $orders = $orders->map(function ($item) use ($lcRequests) {
            return [
                'purchase_order_id' => in_array($item['id'], $lcRequests) ? 'requested' : $item['id'],
                'style_name' => $item['order']->style_name,
                'ship_mode' => $item['order']->ship_mode,
                'po_no' => $item['po_no'],
                'po_quantity' => $item['po_quantity'],
                'rate' => $item['avg_rate_pc_set'],
                'amount' => (double)$item['avg_rate_pc_set'] * (double)$item['po_quantity'],
                'delivery_date' => $item['ex_factory_date'],
                'customer' => $item['customer'],
                'description' => collect($item['poDetails'])->pluck('garmentItem.name')->unique()->values()->implode(', '),
                'co' => 'Bangladesh'

            ];
        });

        return response()->json($orders);
    }

    public function storeDetails(LCRequest $LCRequest, Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $item) {
                if ($id = $item['id'] ?? null) {
                    $LCRequest->details()->find($id)->update($item);
                    continue;
                }
                $LCRequest->details()->create($item);
            }
            DB::commit();
            return response()->json($LCRequest->load('details'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function deleteDetails(LCRequestDetails $LCRequestDetails)
    {
        try {
            $LCRequestDetails->delete();
            return response()->json('success fully delete', Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function view(LCRequest $LCRequest)
    {
        return view('commercial::lc-request.view', $LCRequest->load('details', 'buyer:id,name'));
    }

    public function pdf(LCRequest $LCRequest)
    {
        $pdf = PDF::loadView('commercial::lc-request.pdf', $LCRequest->load('details', 'buyer:id,name'));
        return $pdf->download("{$LCRequest->id}_lc_request.pdf");
    }

    public function print(LCRequest $LCRequest)
    {
        return view('commercial::lc-request.print', $LCRequest->load('details', 'buyer:id,name'));
    }

    public function excel(LCRequest $LCRequest)
    {
        return Excel::download(new LCRequestExport($LCRequest->load('details', 'buyer:id,name')), "{$LCRequest->id}_lc_request.xlsx");
    }

}
