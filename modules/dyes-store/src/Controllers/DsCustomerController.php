<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\DyesStore\Models\DsCustomer;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsCustomerRequest;

class DsCustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = DsCustomer::filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();

        return view('dyes-store::pages.customers', [
            "customers" => $customers
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.customer', ['customer' => null]);
    }

    public function store(DsCustomerRequest $request, DsCustomer $customer)
    {
        try {
            $customer->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('/dyes-store/customers');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(DsCustomer $customer)
    {
        return view('dyes-store::forms.customer', ['customer' => $customer]);
    }

    public function update(DsCustomerRequest $request, DsCustomer $customer)
    {
        try {
            $customer->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('/dyes-store/customers');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy(DsCustomer $customer)
    {
        try {
            $customer->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/dyes-store/customers');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
