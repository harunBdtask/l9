<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\AssigningFactory;
use SkylarkSoft\GoRMG\SystemSettings\Requests\AssigningFactoryRequest;

class AssigningFactoryController extends Controller
{
    public function index()
    {
        $assigningFactories = AssigningFactory::query()->orderBy('id', 'desc')->paginate();
        return view('system-settings::assigning-factory.list', compact('assigningFactories'));
    }

    public function store(AssigningFactoryRequest $request)
    {
        try {
            AssigningFactory::query()->create($request->all());
            Session::flash('success', 'Data Saved Successfully');
        } catch (Exception $e) {
            Session::flash('error', 'Data stored Failed');
        }
        return redirect('assigning-factory');
    }

    public function show($id)
    {
        return AssigningFactory::query()->findOrFail($id);
    }

    public function update($id, AssigningFactoryRequest $request)
    {
        try {
            AssigningFactory::query()->findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully');
        } catch (Exception $e) {
            Session::flash('error', 'Data stored Failed');
        }
        return redirect('assigning-factory');
    }

    public function delete($id)
    {
        AssigningFactory::query()->findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted successfully!!');
        return redirect('assigning-factory');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $assigningFactories = AssigningFactory::query()->where('name', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate();
        return view('system-settings::assigning-factory.list', compact('assigningFactories', 'search'));
    }
}
