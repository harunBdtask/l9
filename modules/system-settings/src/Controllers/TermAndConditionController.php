<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\TermAndCondition;

class TermAndConditionController extends Controller
{
    public function index()
    {
        $data['terms'] = TermAndCondition::pluck('term', 'id');

        return view('system-settings::terms.terms', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'term' => 'required',
            'type' => 'required',
        ]);

        TermAndCondition::firstOrCreate([
            'term' => $request->term,
            'type' => $request->type,
        ]);

        session()->flash('success', 'Successfully Created Terms And Conditions');

        return redirect()->back();
    }
}
