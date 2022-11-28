<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\Models\Company;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\BasicFinance\Requests\UnitFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class UnitController extends Controller
{
    public function index()
    {
        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
        $units = Unit::query()->with('factory', 'project')->orderByDesc('id')->paginate();
        }else{
            $id = (string)(\Auth::id());
            $units = Unit::query()->with('factory', 'project')
                ->whereJsonContains('user_ids', [$id])
                ->orderByDesc('id')->paginate();
        }
        return view('basic-finance::pages.units', ['units' => $units]);
    }

    public function create()
    {
        $data['unit'] = null;
        $data['factories'] = Factory::query()->with('factory', 'unit')->pluck('factory_name', 'id')->all();
        $data['projects'] = Project::query()->where('factory_id',factoryId())->pluck('project', 'id');
        $data['users'] = [];
        return view('basic-finance::forms.unit', $data);
    }

    public function store(UnitFormRequest $request, Unit $unit)
    {
        try {
            $unit->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('basic-finance/units');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function edit( $unit)
    {
        $data['unit'] = $unit =  Unit::where('id',$unit)->first();
        $data['factories'] = Factory::query()->pluck('factory_name', 'id')->all();
        $data['projects'] = Project::query()->pluck('project', 'id')->all();
        $project = Project::query()->where('id', $unit->bf_project_id)->first();
        $data['users'] = [];
        if($project->user_ids !== null){
            $data['users'] = User::query()->orWhereIn('id', $project->user_ids)->pluck('screen_name', 'id')->all();
        }

        return view('basic-finance::forms.unit', $data);
    }

    public function update(UnitFormRequest $request, Unit $unit)
    {
        try {
            $unit->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');
            return redirect('basic-finance/units');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function destroy(Unit $unit)
    {
        try {
            $unit->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('basic-finance/units');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }
}
