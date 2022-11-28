<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Requests\SizeRequest;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::with('factory')->orderBy('sort', 'ASC')->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.sizes', compact('sizes'));
    }

    public function store(SizeRequest $request)
    {
        Size::create($request->all());
        Session::flash('success', 'Data stored successfully!!');

        return redirect('/sizes');
    }

    public function show($id)
    {
        return Size::findOrFail($id);
    }

    public function update($id, SizeRequest $request)
    {
        $size = Size::findOrFail($id);
        $size->update($request->all());
        Session::flash('success', 'Data updated successfully');

        return redirect('/sizes');
    }

    public function destroy($id)
    {
        $sizeIdPoItemColorSizeDetails = PoColorSizeBreakdown::query()->get()->pluck('sizes')->flatten(1)->unique()->values()->map(function ($item) {
            return (int) $item;
        });

        if (! collect($sizeIdPoItemColorSizeDetails)->contains($id)) {
            $size = Size::findOrFail($id);
            $size->delete();
            Session::flash('error', 'Data Deleted successfully!!');
        } else {
            Session::flash('error', 'Can Not be Deleted ! It is currently associated with Others');
        }

        return redirect('/sizes');
    }

    public function getSizes($order_id, $color_id)
    {
        return OrderDetail::getSizes($order_id, $color_id);
    }

    public function searchSizes(Request $request)
    {
        $search = $request->get('search');
        $sizes = Size::where('name', 'like', '%' . $search . '%')
            ->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.sizes', compact('search', 'sizes'));
    }

    public function save(SizeRequest $request)
    {
        try {
            $id = $request->get('id') ?? null;
            if ($id) {
                $data = Size::findOrFail($id);
                $data->update($request->all());
            }else {
                $data = Size::create($request->all());
            }
            return response()->json(['message' => 'Successfully Saved!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()]);
        }
    }
}
