<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\BasicFinance\Models\ReceiveCheque;

class ReceiveChequeController extends Controller
{
    public function index(Request $request)
    {
        $receiveCheque = ReceiveCheque::query()->with('receiveBank')->search($request->get('search'))->orderBy('id', 'desc')->paginate();
        return view('basic-finance::pages.receive_cheques', [
            'cheques' => $receiveCheque,
        ]);
    }

    public function destroy(ReceiveCheque $receiveCheque)
    {
        try {
            $receiveCheque->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('basic-finance/receive-cheques');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");
            return redirect()->back();
        }
    }
}
