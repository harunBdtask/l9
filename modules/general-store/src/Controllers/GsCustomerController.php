<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsCustomer;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsCustomerRequest;

class GsCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = GsCustomer::filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();
        return view('general-store::pages.customers', [
            "customers" => $customers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('general-store::forms.customer', ['customer' => null]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GsCustomerRequest $request
     * @param GsCustomer $customer
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(GsCustomerRequest $request, GsCustomer $customer)
    {
        try {
            $customer->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('customers');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Rack $rack
     * @return \Illuminate\Http\Response
     */
    public function edit(GsCustomer $customer)
    {
        return view('general-store::forms.customer', ['customer' => $customer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GsCustomerRequest $request
     * @param GsCustomer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(GsCustomerRequest $request, GsCustomer $customer)
    {
        try {
            $customer->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');
            return redirect('customers');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GsCustomer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(GsCustomer $customer)
    {
        try {
            $customer->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');
            return redirect('customers');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }
}
