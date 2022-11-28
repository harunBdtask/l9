<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\Models\FundRequisitionPurpose;

class PurposeController extends Controller
{
    public function index()
    {
        $purposes = FundRequisitionPurpose::query()->orderBy('id', 'DESC')->paginate();

        return view('basic-finance::pages.purposes', compact('purposes'));
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $purposes = FundRequisitionPurpose::query()
            ->where('purpose', 'like', '%' . $q . '%')
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('basic-finance::pages.purposes', compact('purposes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'purpose' => 'required',
        ]);
        $purpose = (new FundRequisitionPurpose())->fill($request->all())->save();
        if ($purpose) {
            Session::flash('alert-success', 'Data Stored Successfully!!');
        } else {
            Session::flash('alert-danger', 'Data Update Successfully!!');
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        return FundRequisitionPurpose::query()->findOrFail($id);
    }

    public function update(Request $request, FundRequisitionPurpose $purpose): RedirectResponse
    {
        $request->validate([
            'purpose' => 'required',
        ]);
        $purpose->fill($request->all())->save();
        Session::flash('alert-success', 'Data updated successfully!!');

        return redirect()->back();
    }

    public function destroy(FundRequisitionPurpose $purpose): RedirectResponse
    {
        $purpose->delete();
        Session::flash('alert-success', 'Data deleted successfully!!');

        return redirect()->back();
    }
}
