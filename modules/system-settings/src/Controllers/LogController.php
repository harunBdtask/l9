<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::query()->latest()->paginate(15);

        return view('system-settings::logs.log_list', [
            "logs" => $logs,
        ]);
    }

    public function destroy(): RedirectResponse
    {
        try {
            Log::query()->delete();

            Session::flash('success', 'Data Deleted Successfully!!');
        } catch (\Exception $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }
}
