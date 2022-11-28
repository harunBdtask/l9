<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailConfiguration;

class MailConfigurationsController extends Controller
{
    public function index()
    {
        $mailConfig = MailConfiguration::query()->first();
        return view('system-settings::mail-config.index', compact('mailConfig'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $config = MailConfiguration::query()->firstOrNew();
            $request['is_enabled'] = $request->has('is_enabled') ?? 0;
            $requested_data = $request->filled('password') ? $request->all() : $request->except(['password']);

            $config->fill($requested_data)->save();
            Cache::forget('mail_config');
            Session::flash('alert-success', 'Successfully Updated!');
        } catch (Exception $e) {
            Session::flash('alert-danger', $e->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
