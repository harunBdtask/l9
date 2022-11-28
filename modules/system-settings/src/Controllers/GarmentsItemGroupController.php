<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductCateory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Requests\GarmentsItemGroupRequest;

class GarmentsItemGroupController extends Controller
{
    public function index()
    {
        $garmentsItemGroups = GarmentsItemGroup::query()->orderBy('id', 'desc')->paginate();

        return view('system-settings::pages.garments_item_group', compact('garmentsItemGroups'));
    }

    public function create()
    {
        $garmentsItemGroup = null;

        return view('system-settings::forms.garments_item_group', compact('garmentsItemGroup'));
    }

    public function store(GarmentsItemGroupRequest $request)
    {
        try {
            $data = $request->except('_token');
            $gmtsItem = new GarmentsItemGroup($data);
            $gmtsItem->save();
            Session::flash('alert-success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong');
        }

        return redirect('garments-item-group');
    }

    public function edit($id)
    {
        $garmentsItemGroup = GarmentsItemGroup::query()->findOrFail($id);

        return view('system-settings::forms.garments_item_group', compact('garmentsItemGroup'));
    }

    public function update($id, GarmentsItemGroupRequest $request)
    {
        GarmentsItemGroup::query()->findOrFail($id)->update($request->all());
        Session::flash('alert-success', 'Data Updated Successfully');

        return redirect('garments-item-group');
    }

    public function destroy($id)
    {
        $order = Order::query()->where('garments_item_group', $id)->exists();

        if (!$order) {
            GarmentsItemGroup::query()->findOrFail($id)->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully');
        } else {
            Session::flash('alert-danger', 'Can Not be Deleted ! It is currently associated with Others');
        }

        return redirect('garments-item-group');
    }
}
