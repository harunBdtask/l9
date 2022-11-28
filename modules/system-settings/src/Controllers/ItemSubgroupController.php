<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemSubgroup;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ItemSubgroupRequest;

class ItemSubgroupController extends Controller
{
    public function index(Request $request)
    {
        $itemSubgroups = ItemSubgroup::query()->search($request->get('search'))->latest('id')->paginate();

        return view('system-settings::item-subgroups.item_subgroups', [
            'itemSubgroups' => $itemSubgroups,
            'search' => $request->get('search'),
        ]);
    }

    public function store(ItemSubgroupRequest $request, ItemSubgroup $itemSubgroup)
    {
        try {
            $itemSubgroup->fill($request->all())->save();
            Session::flash('success', 'Data Stored Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/item-subgroups');
    }

    public function edit(ItemSubgroup $itemSubgroup): ItemSubgroup
    {
        return $itemSubgroup;
    }

    public function update(ItemSubgroupRequest $request, ItemSubgroup $itemSubgroup)
    {
        try {
            $itemSubgroup->fill($request->all())->save();
            Session::flash('success', 'Data Update Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/item-subgroups');
    }

    public function destroy(ItemSubgroup $itemSubgroup)
    {
        try {
            $itemSubgroup->delete();
            Session::flash('success', 'Data Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/item-subgroups');
    }
}
