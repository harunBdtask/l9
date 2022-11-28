<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\YarnTypeRequest;

class YarnTypeController extends Controller
{
    public function index()
    {
        $data['yarn_types'] = YarnType::orderBy('created_at', 'desc')
            ->paginate();

        return view('system-settings::knittingdroplets.yarn-types', $data);
    }

    public function create()
    {
        $data['yarn_types'] = null;

        return view('system-settings::knittingdroplets.yarn-type', $data);
    }

    public function editYarnType($id)
    {
        $data['yarn_types'] = YarnType::findOrFail($id);

        return view('system-settings::knittingdroplets.yarn-type', $data);
    }

    public function saveYarnType(YarnTypeRequest $request)
    {
        $id = isset($request->id) ? $request->id : '';

        try {
            DB::beginTransaction();
            $yarn_types = YarnType::findOrNew($id);
            $yarn_types->yarn_type = $request->yarn_type;
            $yarn_types->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('yarn-types');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: YrnTyp.S-101');

            return redirect()->back();
        }
    }

    public function deleteYarnType($id)
    {
        try {
            DB::beginTransaction();
            $yarn_type = YarnType::findOrFail($id);
            $yarn_type->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('yarn-types');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!! ERROR CODE: YrnTyp.D-102');

            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $q = $request->q ?? '';
        if ($q == '') {
            return redirect('/yarn-types');
        }

        $data['yarn_types'] = YarnType::orderBy('created_at', 'desc')
            ->where('yarn_type', 'like', '%'.$q.'%')
            ->paginate();
        $data['q'] = $q;

        return view('system-settings::knittingdroplets.yarn-types', $data);
    }
}
