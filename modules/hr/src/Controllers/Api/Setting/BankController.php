<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\Models\HrBank;
use Symfony\Component\HttpFoundation\Response;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $banks = HrBank::all()->paginate(15);

        return view('hr::banks.index', [
            'banks' => $banks,
            'bank' => null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'branch' => 'required'
        ]);

        try {
            $bank = HrBank::create($request->only('name', 'branch', 'address'));

            Session::flash('success', 'Data Created Successfully');
            return  redirect()->back();
        } catch (Exception $e) {

            Session::flash('error', 'Data Created Failed');
            return  redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param HrBank $bank
     * @return \Illuminate\Http\Response
     */
    public function show(HrBank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HrBank $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(HrBank $bank)
    {
        $banks = HrBank::all()->paginate(15);
        $bank = HrBank::where('id', $bank->id)->first();

        return view('hr::banks.index', [
            'banks' => $banks,
            'bank' => $bank,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param HrBank $bank
     * @return JsonResponse
     */
    public function update(Request $request, HrBank $bank)
    {
        $request->validate([
            'name' => 'required',
            'branch' => 'required'
        ]);

        try {
            $bank->fill($request->only('name', 'branch', 'address'));
            $bank->save();

            Session::flash('success', 'Data Updated Successfully');
            return  redirect('hr/banks/');
        } catch (Exception $e) {
            Session::flash('success', 'Data Create Failed');
            return  redirect('hr/banks/');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HrBank $bank
     * @return JsonResponse
     */
    public function destroy(HrBank $bank)
    {
        try {
            $bank->delete();
        } catch (Exception $e) {
            return \response()->json(['message' => 'Something Went Wrong'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getBanks()
    {
        try {
            $banks = HrBank::get(['id', 'name as text']);
            return response()->json($banks, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
