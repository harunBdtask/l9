<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ItemRequest;

class ItemController extends Controller
{
    public function index()
    {
        $uoms = UnitOfMeasurement::all(['id', 'unit_of_measurement']);
        $items = Item::with('uom_data')->paginate();

        return view('system-settings::item.item_list', compact('items', 'uoms'));
    }

    public function store(ItemRequest $request)
    {
        try {
            Item::create($request->all());
            Session::flash('success', 'Data Stored Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('items');
    }

    public function show($id)
    {
        return Item::findOrFail($id);
    }

    public function update($id, ItemRequest $request)
    {
        try {
            Item::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('items');
    }

    public function destroy($id)
    {
        Item::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted Successfully');

        return redirect('items');
    }

    public function search(Request $request)
    {
        $uoms = UnitOfMeasurement::all(['id', 'unit_of_measurement']);
        $search = $request->get('search');
        $items = Item::with('uom_data')
            ->where('item_name', 'like', '%'.$search.'%')
            ->orWhere('item_manufacturer', 'like', '%'.$search.'%')
            ->orWhere('item_description', 'like', '%'.$search.'%')
            ->orWhereHas('uom_data', function ($query) use ($search) {
                $query->where('unit_of_measurement', 'like', '%'.$search.'%');
            })
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('system-settings::item.item_list', compact('search', 'items', 'uoms'));
    }
}
