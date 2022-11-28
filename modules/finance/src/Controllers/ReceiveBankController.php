<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\ReceiveBank;
use SkylarkSoft\GoRMG\Finance\Requests\ReceiveBankRequest;

class ReceiveBankController extends Controller
{

    public function index(Request $request)
    {
        $receiveBank = ReceiveBank::query()
            ->orderBy('id', 'desc')
            ->search($request->get('search'))
            ->paginate();

        return view('finance::pages.receive_banks', [
            'banks' => $receiveBank,
        ]);
    }

    public function create()
    {
        return view('finance::forms.receive_bank');
    }

    public function store(ReceiveBankRequest $request, ReceiveBank $receiveBank)
    {
        try {
            $receiveBank->fill($request->all())->save();
            Session::flash('success', 'Bank saved successfully!');

            return redirect('/finance/receive-banks');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());

            return back();
        }
    }

    public function edit(ReceiveBank $receiveBank)
    {
        return view('finance::forms.receive_bank', ['bank' => $receiveBank]);
    }

    public function update(ReceiveBankRequest $request, ReceiveBank $receiveBank)
    {
        try {
            $receiveBank->fill($request->all())->save();
            Session::flash('success', 'Bank saved successfully!');

            return redirect('/finance/receive-banks');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());

            return back();
        }
    }

}
