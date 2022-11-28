<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Requests\CurrencyRequest;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::with('preparedBy')->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.currencies', compact('currencies'));
    }

    public function store(CurrencyRequest $request)
    {
        try {
            Currency::create($request->all());
            Session::flash('success', 'Data Created successfully');
        } catch (\Exception $e) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/currencies');
    }

    public function show($id)
    {
        return Currency::findOrFail($id);
    }

    public function update($id, CurrencyRequest $request)
    {
        try {
            Currency::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/currencies');
    }

    public function destroy($id)
    {
        Currency::findOrFail($id)->delete();
        Session::flash('error', 'Data Deleted successfully');

        return redirect('/currencies');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $currencies = Currency::with('preparedBy')->where('currency_name', 'like', '%' . $search . '%')->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.currencies', compact('currencies', 'search'));
    }

    public function getOptions(): JsonResponse
    {
        $currencies = Currency::query()->get(['id', 'currency_name as text']);

        return response()->json($currencies);
    }
}
