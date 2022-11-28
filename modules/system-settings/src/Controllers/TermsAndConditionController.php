<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\TermsAndCondition;
use SkylarkSoft\GoRMG\SystemSettings\Requests\SeasonRequest;
use SkylarkSoft\GoRMG\SystemSettings\Requests\TermsAndConditionRequest;

class TermsAndConditionController extends Controller
{
    public function index()
    {
        $pages = TermsAndCondition::PAGE_NAME;
        $data = TermsAndCondition::query()->latest()->paginate();
//        return $data;
        return view('system-settings::pages.terms_and_conditions', compact('pages', 'data'));
    }

    public function store(TermsAndConditionRequest $request)
    {
        try {
            foreach ($request->get('terms_name') as $key => $term) {
                if ($term) {
                    $data['page_name'] = $request->get('page_name');
                    $data['terms_name'] = $request->get('terms_name')[$key];
                    TermsAndCondition::create($data);
                }
            }
            Session::flash('success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('terms-conditions');
    }

    public function edit(TermsAndCondition $id)
    {
        $pages = TermsAndCondition::PAGE_NAME;
        $term = $id;

        return view('system-settings::render.terms_and_condition', compact('pages', 'term'))->render();
    }

    public function update(TermsAndCondition $terms, TermsAndConditionRequest $request)
    {
        try {
            $terms->fill($request->all())->update();
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
        return redirect('terms-conditions');
    }

    public function destroy(TermsAndCondition $terms)
    {
        try {
            $terms->delete();
            Session::flash('success', S_DELETE_MSG);
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
        return redirect('terms-conditions');
    }

    public function getTermsAndConditions($page_name)
    {
        $terms = TermsAndCondition::query()->where('page_name',$page_name)->get()->pluck('terms_name')->all();
        return response()->json($terms);
    }
}
