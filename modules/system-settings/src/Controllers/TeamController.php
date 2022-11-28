<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Requests\TeamRequest;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $searchKey = $request->get('q');
        $teams = Team::query();

        if (!getRole() == 'super-admin') {
            $teams = $teams->where('factory_id', Auth::user()->factory_id);
        }

        $teams = $teams->with('member')
            ->search($searchKey)
            ->where('role', 'Leader')
            ->groupBy('team_name')
            ->get()
            ->map(function ($team) use ($searchKey) {

                $data = Team::query()
                    ->search($searchKey)
                    ->where('team_name', $team->team_name)
                    ->first(['id', 'team_name', 'short_name', 'project_type', 'status']);

                $data['members'] = Team::with('member')
                    ->search($searchKey)
                    ->where('team_name', $team->team_name)
                    ->get()
                    ->map(function ($member) {
                        return [
                            'id' => $member->member->id,
                            'name' => $member->member->first_name . ' ' . $member->member->last_name,
                            'role' => $member->role,
                        ];
                    });

                return $data;
            });
        $users = User::all();
        $projectTypes = $this->projectTypes();

        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');

        return view('system-settings::pages.teams', compact([
            'users',
            'factories',
            'teams',
            'projectTypes'
        ]));
    }

    public function store(TeamRequest $request)
    {
        foreach ($request->get('member_id') as $key => $member_id) {
            $data['team_name'] = $request->get('team_name');
            $data['short_name'] = $request->get('short_name');
            $data['project_type'] = $request->get('project_type');
            $data['status'] = $request->get('status');
            $data['member_id'] = $request->get('member_id')[$key];
            $data['role'] = $request->get('role')[$key];

            $team = Team::create($data);
            $this->associateWithUpdateOrCreate($request->get('associate_with'), $team);
        }

        return redirect('/teams');
    }

    private function projectTypes(): array
    {
        return [
            'Knit', 'Woven', 'Trims', 'Spinning', 'Aop', 'Sweater', 'Wash', 'Print', 'embroidery','Commercial'
        ];
    }

    public function edit($name)
    {
        $data['team'] = Team::query()
            ->with('teamWiseFactories')
            ->where('team_name', $name)
            ->first([
                'id',
                'team_name',
                'short_name',
                'project_type',
                'status'
            ]);
        $data['members'] = Team::with('member')
            ->where('team_name', $name)
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->member->id,
                    'name' => $member->member->first_name . ' ' . $member->member->last_name,
                    'role' => $member->role,
                ];
            });
        $data['users'] = User::all();
        $data['projectTypes'] = $this->projectTypes();
        $data['factories'] = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $data['associateWith'] = ($data['team'])
            ->teamWiseFactories
            ->pluck('factory_id')
            ->values();

        return view('system-settings::render.team', $data)->render();
    }

    public function update($name, Request $request)
    {
        Team::query()->where('team_name', $name)->delete();

        foreach ($request->get('member_id') as $key => $member_id) {
            $data['team_name'] = $request->get('team_name');
            $data['short_name'] = $request->get('short_name');
            $data['project_type'] = $request->get('project_type');
            $data['status'] = $request->get('status');
            $data['member_id'] = $request->get('member_id')[$key];
            $data['role'] = $request->get('role')[$key];
            $team = Team::query()->create($data);

            $associateWith = $request->get('associate_with');
            // dd($request->get('associate_with'), $team);
            if ($associateWith) {
                $team->teamWiseFactories()->whereNotIn('factory_id', $associateWith)->delete();
                $this->associateWithUpdateOrCreate($request->get('associate_with'), $team);
            }
        }

        return redirect('teams');
    }

    public function associateWithUpdateOrCreate($associateWiths = null, $team)
    {
        foreach ($associateWiths as $associateWith) {
            $team->teamWiseFactories()->updateOrCreate(['factory_id' => $associateWith]);
        }
    }
}
