<?php

namespace SkylarkSoft\GoRMG\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Procurement\Models\ProcurePurchaseOrder;
use SkylarkSoft\GoRMG\Procurement\Models\ProcurePurchaseOrderDetail;
use SkylarkSoft\GoRMG\Procurement\Services\Formatters\PurchaseOrderFormatter;
use Symfony\Component\HttpFoundation\Response;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchaseOrders = ProcurePurchaseOrder::query()
            ->when($request->get('search'), function ($query) use ($request) {
                $query->where('po_number', 'like', '%'.$request->get('search').'%');
            })
            ->latest('id')
            ->paginate();

        return view('procurement::purchase-order.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('procurement::purchase-order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProcurePurchaseOrder $purchaseOrder)
    {
        try {
            DB::beginTransaction();
            $purchaseOrder->fill($request->except('purchase_order_details'))->save();
            $purchaseOrder->poDetails()
                ->createMany($request->input('purchase_order_details'));
            DB::commit();

            return response()->json([
                'message' => 'Procurement Purchase Order stored successfully',
                'data' => $purchaseOrder,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchaseOrder = ProcurePurchaseOrder::with('supplier', 'poDetails.item', 'poDetails.quotation.uom')->find($id);

        return view('procurement::purchase-order.view', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ProcurePurchaseOrder $purchaseOrder, PurchaseOrderFormatter $purchaseOrderFormatter)
    {
        try {
            return response()->json([
                'message' => 'Fetch edited data successfully',
                'data' => $purchaseOrderFormatter->format($purchaseOrder),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProcurePurchaseOrder $purchaseOrder)
    {
        try {
            DB::beginTransaction();
            $purchaseOrder->fill($request->all())->save();

            foreach ($request->input('purchase_order_details') as $detail) {
                $purchaseOrder->poDetails()->updateOrCreate([
                    'id' => $detail['id'] ?? '',
                ], $detail);
            }
            DB::commit();

            return response()->json([
                'message' => 'Procurement requisition updated successfully',
                'data' => $purchaseOrder,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $po = ProcurePurchaseOrder::find($id);
        $po->delete();
        $po->poDetails()->delete();

        Session::flash('success', 'Purchase order deleted successfully');

        return back();
    }

    public function po_details_delete($id)
    {
        $poDetails = ProcurePurchaseOrderDetail::find($id);

        try {
            $poDetails->delete();

            return response()->json([
                'message' => 'Procurement requisition deleted successfully',
                'data' => true,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
