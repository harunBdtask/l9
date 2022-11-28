<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Project;
use SkylarkSoft\GoRMG\Finance\Requests\ProjectFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ProjectController extends Controller
{

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index()
    {
        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
            $projects = Project::query()->with('factory')->orderByDesc('id')->paginate();
        } else {
            $id = (string)(\Auth::id());
            $projects = Project::query()->with('factory')
                ->whereJsonContains('user_ids', [$id])
                ->orderByDesc('id')->paginate();
        }

        return view('finance::pages.projects', ['projects' => $projects]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        $data['project'] = null;
        $data['factories'] = Factory::query()->pluck('factory_name', 'id')->all();
        $data['users'] = User::query()->pluck('screen_name', 'id')->all();

        return view('finance::forms.project', $data);
    }

    /**
     * @param ProjectFormRequest $request
     * @param Project $project
     * @return Application|RedirectResponse|Redirector
     */
    public function store(ProjectFormRequest $request, Project $project)
    {
        try {
            $project->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('finance/projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    /**
     * @param $project
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function edit($project)
    {
        $data['project'] = Project::query()->where('id', $project)->first();
        $data['factories'] = Factory::query()->pluck('factory_name', 'id')->all();
        $data['users'] = User::query()->pluck('screen_name', 'id')->all();

        return view('finance::forms.project', $data);
    }

    /**
     * @param ProjectFormRequest $request
     * @param Project $project
     * @return Application|RedirectResponse|Redirector
     */
    public function update(ProjectFormRequest $request, Project $project)
    {
        try {
            $project->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');
            return redirect('finance/projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

    /**
     * @param Project $project
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('finance/projects');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }

}
