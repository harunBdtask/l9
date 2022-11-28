<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricCompositionDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use SkylarkSoft\GoRMG\SystemSettings\Requests\YarnCountRequest;

class YarnCountController extends Controller
{
    public function index()
    {
        $yarnCounts = YarnCount::with('factory')->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.yarn_counts', compact('yarnCounts'));
    }

    public function store(YarnCountRequest $request)
    {
        try {
            $data = YarnCount::create($request->all());

            if (Request::capture()->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }

            Session::flash('success', 'Data Stored Successfully');
        } catch (Exception $e) {
            Session::flash('error', 'Something went wrong');
        }

        return redirect('yarn-counts');
    }

    public function show($id)
    {
        return YarnCount::findOrFail($id);
    }

    public function update($id, YarnCountRequest $request)
    {
        try {
            YarnCount::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully');
        } catch (Exception $e) {
            Session::flash('error', 'Something went wrong');
        }

        return redirect('yarn-counts');
    }

    public function destroy($id)
    {
        $fabricCompositionDetails = NewFabricCompositionDetail::where('yarn_count_id', $id)->first();

        if (! isset($fabricCompositionDetails)) {
            YarnCount::findOrFail($id)->delete();
            Session::flash('error', 'Data Deleted Successfully');
        } else {
            Session::flash('error', 'Can Not be Deleted ! It is currently associated with Others');
        }

        return redirect('/yarn-counts');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $yarnCounts = YarnCount::with('factory')
            ->where('yarn_count', 'like', '%' . $search . '%')
            ->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.yarn_counts', compact('yarnCounts', 'search'));
    }
}
