<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\Models\CostCenter;
use SkylarkSoft\GoRMG\BasicFinance\Requests\CostCenterFormRequest;

class CostCenterController extends Controller
{
    public function index()
    {
        $costCenters = CostCenter::query()->orderByDesc('id')->paginate();
        return view('basic-finance::pages.cost_centers', ['costCenters' => $costCenters]);
    }

    public function create()
    {
        $data['costCenter'] = null;

        return view('basic-finance::forms.cost_center', $data);
    }

    public function store(CostCenterFormRequest $request, CostCenter $costCenter)
    {
        try {
            $costCenter->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('basic-finance/cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function edit( $costCenter)
    {
        $data['costCenter'] = CostCenter::where('id',$costCenter)->first();
        return view('basic-finance::forms.cost_center', $data);
    }

    public function update(CostCenterFormRequest $request, CostCenter $costCenter)
    {
        try {
            $costCenter->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');
            return redirect('basic-finance/cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function destroy(CostCenter $costCenter)
    {
        try {
            $costCenter->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('basic-finance/cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }
}
