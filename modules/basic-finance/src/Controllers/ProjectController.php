<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\Models\Company;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Requests\ProjectFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
            $projects = Project::query()->with('factory')->orderByDesc('id')->paginate();
        }else{
            $id = (string)(\Auth::id());
            $projects = Project::query()->with('factory')
                ->whereJsonContains('user_ids', [$id])
                ->orderByDesc('id')->paginate();
        }

        return view('basic-finance::pages.projects', ['projects' => $projects]);
    }

    public function create()
    {
        $data['project'] = null;
        $data['factories'] = Factory::query()->pluck('factory_name', 'id')->all();
        $data['users'] = User::query()->pluck('screen_name', 'id')->all();

        return view('basic-finance::forms.project', $data);
    }

    public function store(ProjectFormRequest $request, Project $project)
    {
        try {
            $project->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('basic-finance/projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function edit($project)
    {
        $data['project'] = Project::where('id',$project)->first();
        $data['factories'] = Factory::query()->pluck('factory_name', 'id')->all();
        $data['users'] = User::query()->pluck('screen_name', 'id')->all();

        return view('basic-finance::forms.project', $data);
    }

    public function update(ProjectFormRequest $request, Project $project)
    {
        try {
            $project->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');
            return redirect('basic-finance/projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    public function destroy(Project $project)
    {
        try {
            $project->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('basic-finance/projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }
}
