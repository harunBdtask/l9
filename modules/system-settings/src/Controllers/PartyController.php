<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Party;
use SkylarkSoft\GoRMG\SystemSettings\Models\PartyType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\PartyRequest;

class PartyController extends Controller
{
    public function index()
    {
        $data['party_types'] = PartyType::get();
        $data['parties'] = Party::with('party_types')
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('system-settings::pages.parties', $data);
    }

    public function create()
    {
        $data['parties'] = null;
        $data['party_types'] = PartyType::pluck('party_type', 'id')->all();

        return view('system-settings::forms.party', $data);
    }

    public function store(PartyRequest $request)
    {
        $id = isset($request->id) ? $request->id : '';

        try {
            DB::beginTransaction();
            $party = Party::findOrNew($id);
            $party->party_name = $request->party_name;
            $party->party_type_id = $request->party_type_id;
            $party->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('parties');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!Error Code Prty.S-101');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['party_types'] = PartyType::pluck('party_type', 'id')->all();
        $data['parties'] = Party::where(['id' => $id])->first();

        return view('system-settings::forms.party', $data);
    }

    public function deleteParty($id)
    {
        try {
            DB::beginTransaction();
            $party = Party::findOrFail($id);
            $party->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('parties');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!Error Code Prty.D-102');

            return redirect()->back();
        }
    }
}
