<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Operator;
use SkylarkSoft\GoRMG\SystemSettings\Requests\OperatorRequest;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $data['operators'] = Operator::withoutGlobalScope('factoryId')
            ->join('factories', 'factories.id', 'operators.factory_id')
            ->when($q != '', function ($query) use ($q) {
                return $query->orWhere('factories.factory_name', 'like', '%'. $q .'%')
                    ->orWhere('operators.operator_name', 'like', '%'. $q .'%')
                    ->orWhere('operators.operator_code', 'like', '%'. $q .'%');
            })
            ->select('operators.*')
            ->orderBy('operators.created_at', 'DESC')
            ->paginate();
        $data['q'] = $q;

        return view('system-settings::pages.operators', $data);
    }

    public function create()
    {
        $data['operator'] = null;

        return view('system-settings::forms.operator', $data);
    }

    public function store(OperatorRequest $request)
    {
        $id = isset($request->id) ? $request->id : '';

        try {
            DB::beginTransaction();
            $operator = Operator::findOrNew($id);
            $operator->operator_name = $request->operator_name;
            $operator->operator_type = $request->operator_type;
            $operator->operator_code = $request->operator_code;
            $operator->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('operators');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Data Stored Failed!! Error: PT:101');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $operator = Operator::find($id);

        return view('system-settings::forms.operator', compact('operator'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $operators = Operator::query()
                ->withCount('operatorRolls')
                ->findOrFail($id);

            if ($operators->operator_rolls_count > 0) {
                Session::flash('alert-danger', 'Cannot Delete because knit fabric rolls created for this operator!!');

                return redirect('operators');
            }
            $operators->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('operators');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something Went Wrong!');

            return redirect()->back();
        }
    }
}
