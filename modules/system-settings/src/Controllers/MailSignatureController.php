<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSetting;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailSignature;
use SkylarkSoft\GoRMG\SystemSettings\Requests\MailSettingRequest;

class MailSignatureController extends Controller
{
    public function index(Request $request)
    {
        $signature = MailSignature::query()->first();
        return view('system-settings::mail-signature.create', compact('signature'));
    }

    /**
     * @param Request $request
     * @param MailSignature $mailSetting
     * @return RedirectResponse
     */
    public function store(Request $request, MailSignature $mailSignature): RedirectResponse
    {
        try {
            $request->validate([
                'signature' => 'required',
            ]);
            $signature = $mailSignature->first();
            if (!$signature) {
                $signature = $mailSignature;
            }
            $signature->fill($request->all())->save();
            Session::flash('alert-success', 'Successfully stored');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
