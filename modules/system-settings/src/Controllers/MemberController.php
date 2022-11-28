<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Member;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\TeamMember;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.members', compact('members'));
    }

    public function create($id)
    {
        $member = null;
        $users = User::all()->map(function ($user) {
            return [
                'name' => $user->first_name . ' ' . $user->last_name,
                'id' => $user->id,
            ];
        })->pluck('name', 'id');
        $teams = Team::pluck('team_name', 'id');
        $members = TeamMember::with('team.leader', 'member')->where('team_id', $id)->orderBy('id', 'desc')->paginate();

        return view('system-settings::forms.member', compact('member', 'users', 'teams', 'members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'team_id' => 'required',
        ]);

        try {
            TeamMember::create($request->all());
            Session::flash('alert-success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something went wrong');
        }

        return redirect('teams');
    }

    public function show($id)
    {
        return $id;
    }

    public function edit($id)
    {
        $member = TeamMember::findOrFail($id);
        $users = User::all()->map(function ($user) {
            return [
                'name' => $user->first_name . ' ' . $user->last_name,
                'id' => $user->id,
            ];
        })->pluck('name', 'id');
        $teams = Team::pluck('team_name', 'id');

        return view('system-settings::forms.member', compact('member', 'users', 'teams'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'team_id' => 'required',
        ]);

        try {
            TeamMember::findOrFail($id)->update($request->all());
            Session::flash('alert-success', 'Data Updated Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something went wrong');
        }

        return redirect('teams');
    }

    public function destroy($id)
    {
        eamMember::findOrFail($id)->delete($id);
        Session::flash('alert-danger', 'Data Deleted Successfully');

        return redirect('teams');
    }
}
