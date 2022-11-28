<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\Models\HrShift;
use Symfony\Component\HttpFoundation\Response;

class ShiftController extends Controller
{

    public function index()
    {
        $hrShifts = HrShift::all()->paginate(15);

        return view('hr::shifts.index', ['hrShifts' => $hrShifts, 'hrShift' => null]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        try {
            $shift = HrShift::create($request->only('name', 'start_time', 'end_time'));
            Session::flash('success', 'Data Created successfully');

            return redirect()->back();
        } catch (Exception $e) {
            Session::flash('error', 'Something Went Wrong');

            return redirect()->back();
        }
    }

    public function show(HrShift $shift)
    {
        //
    }

    public function edit(HrShift $shift)
    {
        $hrShifts = HrShift::all()->paginate(15);

        return view('hr::shifts.index', ['hrShifts' => $hrShifts, 'hrShift' => $shift]);
    }


    public function update(Request $request, HrShift $shift)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        try {
            $shift->fill($request->only('name', 'start_time', 'end_time'));
            $shift->save();

            Session::flash('success', 'Data Updated successfully');

            return redirect('hr/shifts');
        } catch (Exception $e) {
            Session::flash('error', 'Something Went Wrong');

            return redirect('hr/shifts');
        }
    }

    public function destroy(HrShift $shift)
    {
        //
    }
}
