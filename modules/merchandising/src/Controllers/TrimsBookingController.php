<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Merchandising\Models\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Requests\TrimsBookingRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Skylarksoft\Ordertracking\Models\Order;
use Skylarksoft\Systemsettings\Models\ItemGroup;
use Skylarksoft\Systemsettings\Models\ItemGroupAssign;

class TrimsBookingController extends Controller
{
    public function index(Request $request)
    {
        $trims_bookings = TrimsBooking::orderBy('id', 'desc');
        if ($request->has('purchase_order_id')) {
            $trims_bookings->where('po_id', $request->purchase_order_id);
        }
        $trims_bookings = $trims_bookings->paginate();

        return view('merchandising::trims-booking.pages.trims_bookings', [
            'trims_bookings' => $trims_bookings,
            'po_id' => $request->purchase_order_id,
        ]);
    }

    public function create(Request $request)
    {
        $trims_booking = null;
        $po_id = $request->purchase_order_id;
        $item_groups = ItemGroup::pluck('item_group_name', 'id')->all();
        $brands = DB::table('brands')->pluck('brand_name', 'id')->all();
        $nominated_suppliers = Supplier::pluck('supplier_name', 'id')->all();
        $items = Item::pluck('item_name', 'id')->all();
        $po = Order::findOrFail($po_id);
        $requisition_no = time().userId();

        return view('merchandising::trims-booking.forms.trims_booking', [
            'trims_booking' => $trims_booking,
            'item_groups' => $item_groups,
            'brands' => $brands,
            'items' => $items,
            'nominated_suppliers' => $nominated_suppliers,
            'po_id' => $po_id,
            'po' => $po,
            'requisition_no' => $requisition_no,
        ]);
    }

    public function store(TrimsBookingRequest $request)
    {
        $count = sizeof($request->item_id);
        $po_id = $request->po_id;
        $requisition_no = $request->requisition_no;
        $dateTime = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $trimsDetailsInput[] = [
                'po_id' => $po_id,
                'requisition_no' => $requisition_no,
                'item_group_id' => $request->item_group_id[$i],
                'item_id' => $request->item_id[$i],
                'amount' => $request->amount[$i],
                'brand_id' => $request->brand_id[$i] ?? null,
                'nominated_supplier_id' => $request->nominated_supplier_id[$i] ?? null,
                'is_approved' => $request->is_approved[$i],
                'factory_id' => factoryId(),
                'created_by' => userId(),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,


            ];
        }

        \DB::transaction(function () use ($trimsDetailsInput) {
            try {
                TrimsBooking::insert($trimsDetailsInput);
                Session::flash('success', S_SAVE_MSG);
            } catch (Exception $e) {
                Session::flash('error', $e->getMessage());
            }
        });

        return redirect('purchase-order/trims-requisition-list?purchase_order_id='.$po_id);
    }

    public function edit($requisition_no)
    {
        $trims_booking = TrimsBooking::where('requisition_no', $requisition_no)->get();
        $item_groups = ItemGroup::pluck('item_group_name', 'id')->all();
        $brands = DB::table('brands')->pluck('brand_name', 'id')->all();
        $nominated_suppliers = Supplier::pluck('supplier_name', 'id')->all();

        $items = null;

        return view('merchandising::trims-booking.forms.trims_booking', [
            'trims_booking' => $trims_booking,
            'item_groups' => $item_groups,
            'items' => $items,
            'brands' => $brands,
            'nominated_suppliers' => $nominated_suppliers,
            'po_id' => $trims_booking->first()->po_id,
            'requisition_no' => $requisition_no,
        ]);
    }

    public function update($requisition_no, TrimsBookingRequest $request)
    {
        try {
            DB::beginTransaction();
            $count = sizeof($request->item_group_id);

            for ($i = 0; $i < $count; $i++) {
                $trimsDetailsInput = [
                    'item_group_id' => $request->item_group_id[$i],
                    'item_id' => $request->item_id[$i],
                    'amount' => $request->amount[$i],
                    'brand_id' => $request->brand_id[$i] ?? null,
                    'nominated_supplier_id' => $request->nominated_supplier_id[$i] ?? null,
                    'is_approved' => $request->is_approved[$i],
                ];

                if (isset($request->id[$i])) {
                    $trimsDetailsInput['updated_by'] = userId();
                    TrimsBooking::where('id', $request->id[$i])->update($trimsDetailsInput);
                } else {
                    $trimsDetailsInput['requisition_no'] = $request->requisition_no;
                    $trimsDetailsInput['po_id'] = $request->po_id;
                    $trimsDetailsInput['created_by'] = userId();

                    $isExist = TrimsBooking::where(['item_id' => $request->item_id[$i], 'requisition_no' => $request->requisition_no])
                       ->count();

                    if (! $isExist) {
                        TrimsBooking::Create($trimsDetailsInput);
                    } else {
                        Session::flash('error', 'New Item Already Exist');

                        return redirect()->back();
                    }
                }
            }

            if (sizeof($request->item_group_id) > 0) {
                TrimsBooking::destroy($request->delete_id);
            }

            DB::commit();
            Session::flash('success', S_SAVE_MSG);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', $e->getMessage());
        }

        return redirect('purchase-order/trims-requisition-list?purchase_order_id='.$request->po_id);
    }

    public function destroy($requisition_no)
    {
        $data = TrimsBooking::where('requisition_no', $requisition_no);
        $data->delete();

        Session::flash('success', S_DELETE_MSG);

        return redirect()->back();
    }

    public function searchTrimsRequisition(Request $request)
    {
        $trims_bookings = TrimsBooking::orderBy('id', 'desc')
            ->where('requisition_no', 'like', '%' . $request->q . '%')
            ->paginate();

        return view('merchandising::trims-booking.pages.trims_bookings', [
            'trims_bookings' => $trims_bookings,
            'q' => $request->q,
            'po_id' => $request->po_id,
        ]);
    }
}
