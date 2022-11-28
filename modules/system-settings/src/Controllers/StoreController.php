<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\Stores;
use SkylarkSoft\GoRMG\SystemSettings\Models\TrimsAccessoriesItem;
use SkylarkSoft\GoRMG\SystemSettings\Requests\StoreRequest;
use SkylarkSoft\GoRMG\SystemSettings\Requests\TrimsAccessoriesRequest;
use Illuminate\Http\Request;

class StoreController extends Controller
{


    public function index()
    {
        $stores = Stores::orderBy('id', 'desc')->paginate(6);
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id')->all();
        $items = Item::pluck('item_name', 'id')->all();
        return view('system-settings::pages.stores', compact('stores', 'factories', 'items'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $data = $request->all();
            Stores::create($data);
            Session::flash('success', 'Data Created Successfully');
        } catch (\Exception $exception){
            Session::flash('danger', 'Something went wrong');
        }

        return redirect('stores');
    }

    public function show($id)
    {
        return Stores::findOrFail($id);
    }

    public function update($id, StoreRequest $request)
    {
        Stores::findOrFail($id)->update($request->all());
        Session::flash('success', 'Data Update Successfully');
        return redirect('stores');
    }

    public function destroy($id)
    {
        Stores::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted Successfully');
        return redirect('stores');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id')->all();
        $items = Item::pluck('item_name', 'id')->all();
        $stores = Stores::with('factory', 'item')
            ->where('name', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->paginate();
        return view('system-settings::pages.stores', compact('stores', 'search', 'factories', 'items'));
    }

    public function fetchAddress(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }
        $factory_id = $request->factory_id;
        $factory_address = Factory::query()->where('id', $factory_id)->get([
                'id',
                'factory_address as text'
            ])->first();
        return response()->json($factory_address);
    }
}
