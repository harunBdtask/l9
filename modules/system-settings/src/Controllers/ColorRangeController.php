<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorRange;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ColorRangeRequest;

class ColorRangeController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $data['color_ranges'] = ColorRange::when($q != '', function ($query) use ($q) {
            return $query->where('name', 'like', '%' . $q . '%');
        })->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.color_ranges', $data);
    }

    public function create()
    {
        $data['color_range'] = null;

        return view('system-settings::forms.color_range', $data);
    }

    public function store(ColorRangeRequest $request)
    {
        try {
            DB::beginTransaction();
            $color_range = new ColorRange();
            $color_range->name = $request->name;
            $color_range->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('color-ranges');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function update($id, ColorRangeRequest $request)
    {
        $colorRangeId = $request->id ?? '';

        try {
            DB::beginTransaction();
            $color_range = ColorRange::findOrNew($colorRangeId);
            $color_range->name = $request->name;
            $color_range->save();
            DB::commit();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('color-ranges');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['color_range'] = ColorRange::findOrFail($id);

        return view('system-settings::forms.color_range', $data);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $color_range = ColorRange::findOrFail($id);
            $color_range->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('color-ranges');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
