<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\ReceiveCheque;

class ReceiveChequeController extends Controller
{

    public function index(Request $request)
    {
        $receiveCheque = ReceiveCheque::query()
            ->with('receiveBank')
            ->orderBy('id', 'desc')
            ->search($request->get('search'))
            ->paginate();

        return view('finance::pages.receive_cheques', [
            'cheques' => $receiveCheque,
        ]);
    }

    public function destroy(ReceiveCheque $receiveCheque)
    {
        try {
            $receiveCheque->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('finance/receive-cheques');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!! {$exception->getMessage()}");

            return redirect()->back();
        }
    }

}
