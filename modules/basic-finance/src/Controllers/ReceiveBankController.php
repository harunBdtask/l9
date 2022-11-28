<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\Models\ReceiveBank;
use SkylarkSoft\GoRMG\BasicFinance\Requests\ReceiveBankRequest;

class ReceiveBankController extends Controller
{
    public function index(Request $request)
    {
        $receiveBank = ReceiveBank::query()->search($request->get('search'))->orderBy('id', 'desc')->paginate();

        return view('basic-finance::pages.receive_banks', [
            'banks' => $receiveBank,
        ]);
    }

    public function create()
    {
        return view('basic-finance::forms.receive_bank');
    }

    public function store(ReceiveBankRequest $request, ReceiveBank $receiveBank)
    {
        try {
            $receiveBank->fill($request->all())->save();
            Session::flash('success', 'Bank saved successfully!');

            return redirect('/basic-finance/receive-banks');
        } catch (\Exception $exception) {
            Session::flash('error', $exception->getMessage());

            return back();
        }
    }

    public function edit(ReceiveBank $receiveBank)
    {
        return view('basic-finance::forms.receive_bank', ['bank' => $receiveBank]);
    }

    public function update(ReceiveBankRequest $request, ReceiveBank $receiveBank)
    {
        try {
            $receiveBank->fill($request->all())->save();
            Session::flash('success', 'Bank saved successfully!');

            return redirect('/basic-finance/receive-banks');
        } catch (\Exception $exception) {
            Session::flash('error', $exception->getMessage());

            return back();
        }
    }
}
