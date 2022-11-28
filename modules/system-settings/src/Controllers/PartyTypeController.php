<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\PartyType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\PartyTypeRequest;

class PartyTypeController extends Controller
{
    public function index()
    {
        $partyTypes = PartyType::with('factory')->orderBy('created_at', 'DESC')->paginate();

        return view('system-settings::pages.party-types', compact('partyTypes'));
    }

    public function store(PartyTypeRequest $request)
    {
        try {
            PartyType::create($request->all());
            Session::flash('success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('party-types');
    }

    public function show($id)
    {
        return PartyType::findOrFail($id);
    }

    public function update($id, PartyTypeRequest $request)
    {
        try {
            PartyType::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('party-types');
    }

    public function destroy($id)
    {
        PartyType::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted Successfully');

        return redirect('party-types');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $partyTypes = PartyType::with('factory')->where('party_type', 'like', '%' . $search . '%')->orderBy('created_at', 'DESC')->paginate();

        return view('system-settings::pages.party-types', compact('partyTypes', 'search'));
    }
}
