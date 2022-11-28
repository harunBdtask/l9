<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinancialParameterSetup;
use SkylarkSoft\GoRMG\SystemSettings\Requests\FinancialParameterSetupRequest;

class FinancialParameterSetupController extends Controller
{
    public function index()
    {
        $parameters = FinancialParameterSetup::with('factory')->orderBy('id', 'desc')->paginate();

        return view('system-settings::pages.financial_parameters', compact('parameters'));
    }

    public function create()
    {
        $parameter = null;
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');

        return view('system-settings::forms.financial_parameter', compact('parameter', 'factories'));
    }

    public function workingDayCount(Request $request): int
    {
        $dateFrom = Carbon::parse(explode(' ', $request->get('range'))[0]);
        $dateTo = Carbon::parse(explode(' ', $request->get('range'))[2]);
        $days = $dateFrom->diffInDaysFiltered(function (Carbon $date) {
            return ! $date->isFriday();
        }, $dateTo);

        return $days + 1;
    }

    public function store(FinancialParameterSetupRequest $request)
    {
        try {
            $data = $request->except('_token', 'applying_period');
            $data['date_from'] = date_format(date_create(explode(' ', $request->get('applying_period'))[0]), 'Y-m-d');
            $data['date_to'] = date_format(date_create(explode(' ', $request->get('applying_period'))[2]), 'Y-m-d');
            FinancialParameterSetup::create($data);
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');

            return $exception->getMessage();
        }

        return redirect('financial-parameter-setups');
    }

    public function edit($id)
    {
        $parameter = FinancialParameterSetup::findOrFail($id);
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');

        return view('system-settings::forms.financial_parameter', compact('parameter', 'factories'));
    }

    public function update($id, FinancialParameterSetupRequest $request)
    {
        try {
            $data = $request->except('_token', 'applying_period');
            $data['date_from'] = date_format(date_create(explode(' ', $request->get('applying_period'))[0]), 'Y-m-d');
            $data['date_to'] = date_format(date_create(explode(' ', $request->get('applying_period'))[2]), 'Y-m-d');
            FinancialParameterSetup::findOrFail($id)->update($data);
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('financial-parameter-setups');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $parameters = FinancialParameterSetup::with('factory')
            ->where('working_day', 'like', '%' . $search . '%')
            ->orWhere('bep_cm', 'like', '%' . $search . '%')
            ->orWhere('asking_profit', 'like', '%' . $search . '%')
            ->orWhere('factory_machine', 'like', '%' . $search . '%')
            ->orWhere('monthly_cm_expense', 'like', '%' . $search . '%')
            ->orWhere('working_hour', 'like', '%' . $search . '%')
            ->orWhere('cost_per_minute', 'like', '%' . $search . '%')
            ->orWhere('actual_cm', 'like', '%' . $search . '%')
            ->orWhere('asking_avg_rate', 'like', '%' . $search . '%')
            ->orWhere('max_profit', 'like', '%' . $search . '%')
            ->orWhere('depreciation_amortization', 'like', '%' . $search . '%')
            ->orWhere('interest_expenses', 'like', '%' . $search . '%')
            ->orWhere('income_tax', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')->paginate();

        return view('system-settings::pages.financial_parameters', compact('parameters', 'search'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $financialParameter = FinancialParameterSetup::findOrFail($id);
            $financialParameter->delete();
            DB::commit();
            Session::flash('error', 'Data Deleted Successfully!!');

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Went Wrong');

            return redirect()->back();
        }
    }
}
