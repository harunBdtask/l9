<?php

namespace SkylarkSoft\GoRMG\SecurityControl\Controllers;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\SecurityControl\Models\Visitor;

class VisitorController extends Controller
{
    public function index()
    {
        $data['visitor_edit'] = null;
        $data['visitors'] = Visitor::orderBy('id', 'desc')->paginate(5);

        return view('securitycontrol::pages.visitor_management_system', $data);
    }

    public function edit($id)
    {
        $data['visitors'] = Visitor::orderBy('id', 'desc')->paginate(5);
        $data['visitor_edit'] = Visitor::find($id);

        return view('securitycontrol::pages.visitor_management_system', $data);
    }

    public function store(Request $request, $id = null)
    {
        if ($id == null) {
            $request->validate([
                'name' => 'required',
                'designation' => 'required',
                'company_name' => 'required',
                'meeting_person' => 'required',
                'email' => ['nullable','email',Rule::unique('visitors_tracking')->where(function ($query) {
                    return $query->where('status', true);
                })],
                'mobile_number' => ['required','numeric','regex:/(01)[0-9]{9}/',Rule::unique('visitors_tracking')->where(function ($query) {
                    return $query->where('status', true);
                })],
            ]);
            $visitor = new Visitor();
            $visitor->registration_id = $this->generateRegistrationNo();
        } else {
            $visitor = Visitor::find($id);
            $request->validate([
                'name' => 'required',
                'designation' => 'required',
                'company_name' => 'required',
                'meeting_person' => 'required',
                'email' => ['nullable','email',Rule::unique('visitors_tracking')->where(function ($query) {
                    return $query->where('status', true);
                })->ignore($visitor->id)],
                'mobile_number' => ['required','numeric','regex:/(01)[0-9]{9}/',Rule::unique('visitors_tracking')->where(function ($query) {
                    return $query->where('status', true);
                })->ignore($visitor->id)],
               ]);
        }
        $visitor->name = $request->name;
        $visitor->designation = $request->designation;
        $visitor->company_name = $request->company_name;
        $visitor->mobile_number = $request->mobile_number;
        $visitor->email = $request->email;
        $visitor->meeting_person = $request->meeting_person;
        $visitor->status = true;
        $visitor->in_time = now();
        $visitor->save();
        if ($id == null) {
            Toastr::success('Visitor saved Successfully');
        } else {
            Toastr::success('Visitor updated Successfully');
        }

        return redirect()->route('visitor.index');
    }

    public function delete($id)
    {
        Visitor::find($id)->delete();
        Toastr::success('Visitor deleted Successfully');

        return redirect()->route('visitor.index');
    }

    public function statusUpdate($id)
    {
        $visitor = Visitor::find($id);
        $visitor->status = false;
        $visitor->out_time = now();
        $visitor->save();
        Toastr::success('Visitor Checked Out');

        return redirect()->route('visitor.index');
    }

    public function show($id)
    {
        $data['visitor'] = Visitor::find($id);

        return view('securitycontrol::pages.visitor_tracking', $data);
    }

    public function scan(Request $request)
    {
        $registration = $request->id;
        $data = Visitor::where('registration_id', $registration)->where('status', true)->first();

        if ($data) {
            if ($data['status'] == true) {
                Visitor::where('registration_id', $registration)->where('status', true)->update([
                    'status' => false,
                ]);

                return response()->json(['status' => 'Success', 'status-id' => $registration]);
            } else {
                return response()->json(['status' => 'Warning', 'status-id' => $registration]);
            }
        }
    }

    private function generateRegistrationNo()
    {
        return mt_rand(100000, 999999);
    }
}
