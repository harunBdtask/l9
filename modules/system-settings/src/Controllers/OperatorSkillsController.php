<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\OperatorSkill;
use SkylarkSoft\GoRMG\SystemSettings\Requests\OperatorSkillsRequest;

class OperatorSkillsController extends Controller
{
    public function index()
    {
        $parts = OperatorSkill::orderBy('id', 'DESC')->paginate();

        return view('system-settings::iedroplets.operator_skills', ['parts' => $parts]);
    }

    public function create()
    {
        return view('system-settings::iedroplets.operator_skill', ['operator_skills' => null]);
    }

    public function store(OperatorSkillsRequest $request)
    {
        OperatorSkill::create($request->all());

        return redirect('/operator-skill');
    }

    public function edit($id)
    {
        $operator_skills = OperatorSkill::findOrFail($id);

        return view('system-settings::iedroplets.operator_skill', ['operator_skills' => $operator_skills]);
    }

    public function update($id, OperatorSkillsRequest $request)
    {
        $part = OperatorSkill::findOrFail($id);
        $part->update($request->all());

        return redirect('/operator-skill');
    }

    public function destroy($id)
    {
        $part = OperatorSkill::findOrFail($id);
        $part->delete();

        return redirect('/operator-skill');
    }

    public function searchOperatorSkills(Request $request)
    {
        $parts = OperatorSkill::where('name', 'like', '%' . $request->q . '%')
            ->paginate();

        return view('system-settings::iedroplets.operator_skills', ['parts' => $parts, 'q' => $request->q]);
    }
}
