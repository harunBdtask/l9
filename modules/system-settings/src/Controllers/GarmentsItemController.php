<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductCateory;
use SkylarkSoft\GoRMG\SystemSettings\Requests\GarmentsItemRequest;

class GarmentsItemController extends Controller
{
    public function index()
    {
        $garmentsItems = GarmentsItem::with('productCategory')->orderBy('id', 'desc')->paginate();

        return view('system-settings::pages.garments_items', compact('garmentsItems'));
    }

    public function create()
    {
        $garmentsItem = null;
        $productCategories = ProductCateory::pluck('category_name', 'id');

        return view('system-settings::forms.garments_item', compact('garmentsItem', 'productCategories'));
    }

    public function store(GarmentsItemRequest $request)
    {
        try {
            $data = $request->except('_token');
            $gmtsItem = new GarmentsItem($data);
            $gmtsItem->save();
            if (!$request->ajax()) {
                Session::flash('alert-success', 'Data Created Successfully');
            }
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong');
            $gmtsItem = $exception->getMessage();
        }

        if ($request->ajax()) {
            return response()->json($gmtsItem);
        }
        return redirect('garments-items');
    }

    public function edit($id)
    {
        $garmentsItem = GarmentsItem::findOrFail($id);
        $productCategories = ProductCateory::pluck('category_name', 'id');

        return view('system-settings::forms.garments_item', compact('garmentsItem', 'productCategories'));
    }

    public function update($id, GarmentsItemRequest $request)
    {
        GarmentsItem::findOrFail($id)->update($request->all());
        Session::flash('alert-success', 'Data Updated Successfully');

        return redirect('garments-items');
    }

    public function destroy($id)
    {
        $priceQuotation = PriceQuotation::query()->get();
        $garmentsItemIdPQ = collect($priceQuotation)->pluck('item_details')->flatten(1)->pluck('garment_item_id')->map(function ($item) {
            return (int)$item;
        })->unique()->values();

        $orders = Order::query()->get();
        $garmentsItemIdOrders = collect($orders)->pluck('item_details.details')->flatten(1)->pluck('item_id')->map(function ($item) {
            return (int)$item;
        })->unique()->values();

        if (!(collect($garmentsItemIdPQ)->contains($id) || collect($garmentsItemIdOrders)->contains($id))) {
            GarmentsItem::findOrFail($id)->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully');
        } else {
            Session::flash('alert-danger', 'Can Not be Deleted ! It is currently associated with Others');
        }

        return redirect('garments-items');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $garmentsItems = GarmentsItem::with('productCategory')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('commercial_name', 'like', '%' . $search . '%')
            ->orWhere('product_type', 'like', '%' . $search . '%')
            ->orWhere('standard_smv', 'like', '%' . $search . '%')
            ->orWhere('efficiency', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->orWhereHas('productCategory', function ($query) use ($search) {
                $query->where('category_name', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc')->paginate();

        return view('system-settings::pages.garments_items', compact('garmentsItems', 'search'));
    }

    public function save(Request $request)
    {
        try {
            if (empty($request->commercial_name)) {
                $request['commercial_name'] = $request->name;
            }
            $id = $request->get('id') ?? null;
            if ($id) {
                $data = GarmentsItem::findOrFail($id);
                $data->update($request->all());
            }else {
                $data = GarmentsItem::create($request->all());
            }
            return response()->json(['message' => 'Successfully Saved!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()]);
        }
    }
}
