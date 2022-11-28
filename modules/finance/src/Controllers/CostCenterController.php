<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\CostCenter;
use SkylarkSoft\GoRMG\Finance\Requests\CostCenterFormRequest;

class CostCenterController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $costCenters = CostCenter::query()->orderByDesc('id')->paginate();

        return view('finance::pages.cost_centers', [
            'costCenters' => $costCenters
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $data['costCenter'] = null;

        return view('finance::forms.cost_center', $data);
    }

    /**
     * @param CostCenterFormRequest $request
     * @param CostCenter $costCenter
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CostCenterFormRequest $request, CostCenter $costCenter)
    {
        try {
            $costCenter->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('finance/cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

    /**
     * @param $costCenter
     * @return Application|Factory|View
     */
    public function edit($costCenter)
    {
        $data['costCenter'] = CostCenter::query()
            ->where('id', $costCenter)
            ->first();

        return view('finance::forms.cost_center', $data);
    }

    /**
     * @param CostCenterFormRequest $request
     * @param CostCenter $costCenter
     * @return Application|RedirectResponse|Redirector
     */
    public function update(CostCenterFormRequest $request, CostCenter $costCenter)
    {
        try {
            $costCenter->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');

            return redirect('finance/cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

    /**
     * @param CostCenter $costCenter
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(CostCenter $costCenter)
    {
        try {
            $costCenter->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('finance/cost-centers');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

}
