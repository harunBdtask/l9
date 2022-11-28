<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Unit;
use SkylarkSoft\GoRMG\Finance\Models\Project;
use SkylarkSoft\GoRMG\Finance\Requests\UnitFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class UnitController extends Controller
{

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index()
    {
        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
            $units = Unit::query()->with('factory', 'project')
                ->orderByDesc('id')
                ->paginate();
        } else {
            $id = (string)(\Auth::id());
            $units = Unit::query()->with('factory', 'project')
                ->whereJsonContains('user_ids', [$id])
                ->orderByDesc('id')
                ->paginate();
        }

        return view('finance::pages.units', ['units' => $units]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        $data['unit'] = null;

        $data['factories'] = Factory::query()
            ->with('factory', 'unit')
            ->pluck('factory_name', 'id')
            ->all();

        $data['projects'] = Project::query()
            ->where('factory_id', factoryId())
            ->pluck('project', 'id');

        $data['users'] = [];

        return view('finance::forms.unit', $data);
    }

    /**
     * @param UnitFormRequest $request
     * @param Unit $unit
     * @return Application|RedirectResponse|Redirector
     */
    public function store(UnitFormRequest $request, Unit $unit)
    {
        try {
            $unit->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('finance/units');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

    /**
     * @param $unit
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function edit($unit)
    {
        $data['unit'] = $unit = Unit::query()->where('id', $unit)->first();
        $data['factories'] = Factory::query()->pluck('factory_name', 'id')->all();
        $data['projects'] = Project::query()->pluck('project', 'id')->all();
        $project = Project::query()->where('id', $unit->fi_project_id)->first();
        $data['users'] = [];

        if ($project->user_ids !== null) {
            $data['users'] = User::query()
                ->orWhereIn('id', $project->user_ids)
                ->pluck('screen_name', 'id')
                ->all();
        }

        return view('finance::forms.unit', $data);
    }

    /**
     * @param UnitFormRequest $request
     * @param Unit $unit
     * @return Application|RedirectResponse|Redirector
     */
    public function update(UnitFormRequest $request, Unit $unit)
    {
        try {
            $unit->fill($request->all())->save();
            Session::flash('alert-success', 'Data Update Successfully!!');

            return redirect('finance/units');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

    /**
     * @param Unit $unit
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(Unit $unit)
    {
        try {
            $unit->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('finance/units');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

}
