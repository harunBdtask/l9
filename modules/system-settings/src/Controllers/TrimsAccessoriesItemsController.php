<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\TrimsAccessoriesItem;
use SkylarkSoft\GoRMG\SystemSettings\Requests\TrimsAccessoriesRequest;

class TrimsAccessoriesItemsController extends Controller
{
    public function index()
    {
        $trimsAndAccessories = TrimsAccessoriesItem::with('user')->orderBy('id', 'desc')->paginate();

        return view('system-settings::pages.trims_accessories_items', compact('trimsAndAccessories'));
    }

    public function store(TrimsAccessoriesRequest $request)
    {
        try {
            $data = $request->except('_token');
            TrimsAccessoriesItem::create($data);
            Session::flash('success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('danger', 'Something went wrong');
        }

        return redirect('trims-accessories-item');
    }

    public function show($id)
    {
        return TrimsAccessoriesItem::findOrFail($id);
    }

    public function update($id, TrimsAccessoriesRequest $request)
    {
        TrimsAccessoriesItem::findOrFail($id)->update($request->all());
        Session::flash('success', 'Data Update Successfully');

        return redirect('trims-accessories-item');
    }

    public function destroy($id)
    {
        TrimsAccessoriesItem::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted Successfully');

        return redirect('trims-accessories-item');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $trimsAndAccessories = TrimsAccessoriesItem::with('user')
            ->where('name', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->paginate();

        return view('system-settings::pages.trims_accessories_items', compact('trimsAndAccessories', 'search'));
    }
}
