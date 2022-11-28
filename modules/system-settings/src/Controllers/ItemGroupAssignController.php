<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroupAssign as ItemGroupAssignModel;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ItemGroupAssign;

class ItemGroupAssignController extends Controller
{
    public function index()
    {
        $item_details = ItemGroupAssignModel::orderBy('item_group_id', 'desc')->paginate();
        return view('system-settings::item-to-group.item_group_list', ['item_details' => $item_details]);
    }

    public function assignItemToGroup()
    {
        if (getRole() == 'super-admin') {
            $factories = Factory::pluck('factory_name', 'id');
        } else {
            $factories = [];
            $data['factory_id'] = Auth::user()->factory_id;
        }

        $data['groups'] = ItemGroup::pluck('item_group_name', 'id');
        $data['factories'] = $factories;
        $data['item_group_assign'] = null;
        $data['items'] = Item::where('status', 1)->pluck('item_name', 'id');

        return view('system-settings::item-to-group.assign_item_to_group', $data);
    }

    public function itemGroupAssign(ItemGroupAssign $request)
    {
        $is_insert = ItemGroupAssignModel::create($request->all());

        if ($is_insert) {
            Session::flash('alert-success', 'Data Stored Successfully!!');
        } else {
            Session::flash('alert-danger', 'Data Stored Successfully!!');
        }

        return redirect('item-to-group');
    }

    public function edit($id)
    {
        $data['item_group_assign'] = ItemGroupAssignModel::find($id);
        if (getRole() == 'super-admin') {
            $factories = Factory::pluck('factory_name', 'id');
            $data['groups'] = ItemGroup::where(['factory_id' => $data['item_group_assign']->factory_id])->pluck('item_group_name', 'id');
        } else {
            $factories = [];
            $data['factory_id'] = Auth::user()->factory_id;
        }
        $data['factories'] = $factories;
        $data['groups'] = ItemGroup::pluck('item_group_name', 'id');
        $data['items'] = Item::where('status', 1)->pluck('item_name', 'id');

        return view('system-settings::item-to-group.assign_item_to_group', $data);
    }

    public function update(ItemGroupAssign $request)
    {
        $is_update = ItemGroupAssignModel::findorFail($request->id)->update($request->all());
        if ($is_update) {
            Session::flash('alert-success', 'Data Updated Successfully!!');
        } else {
            Session::flash('alert-danger', 'Data Updated Failed!!');
        }

        return redirect('item-to-group');
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $shifts = ItemGroupAssignModel::findOrFail($id);
            $shifts->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('item-to-group');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!Error Code Prty.D-102');

            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $search_column = $request->search_column;
        if ($q == '' || $search_column == '') {
            return redirect('item-to-group');
        }

        $item_details = ItemGroupAssignModel::when($search_column == 'item_id', function ($query) use ($q) {
            return $query->whereHas('item', function ($query) use ($q) {
                return $query->where('item_name', 'like', '%' . $q . '%');
            });
        })->when($search_column == 'item_group_id', function ($query) use ($q) {
            return $query->whereHas('group', function ($query) use ($q) {
                return $query->where('item_group_name', 'like', '%' . $q . '%');
            });
        })
            ->orderBy('id', 'desc')
            ->paginate();

        return view('system-settings::item-to-group.item_group_list', [
            'item_details' => $item_details,
            'q' => $q,
            'search_column' => $search_column,
        ]);
    }
}
