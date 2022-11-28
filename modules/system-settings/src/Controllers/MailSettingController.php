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
use SkylarkSoft\GoRMG\SystemSettings\Requests\MailSettingRequest;

class MailSettingController extends Controller
{
    public function index(Request $request)
    {
        $searchType = $request->get('search_type');

        $settings = MailSetting::query()
            ->when($searchType, function ($query) use ($searchType) {
                $query->where('mail_type', $searchType);
            })
            ->paginate();
        $groups = MailGroup::query()->pluck('name', 'id');
        $types = MailSetting::MAIL_TYPE;

        return view('system-settings::mail-settings.create', compact('settings', 'groups', 'types'));
    }

    /**
     * @param MailSettingRequest $request
     * @param MailSetting $mailSetting
     * @return RedirectResponse
     */
    public function store(MailSettingRequest $request, MailSetting $mailSetting): RedirectResponse
    {
        try {
            $mailSetting->fill($request->all())->save();
            Session::flash('alert-success', 'Successfully stored');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    /**
     * @param MailSetting $mailSetting
     * @return JsonResponse
     */
    public function edit(MailSetting $mailSetting): JsonResponse
    {
        return response()->json($mailSetting);
    }


    /**
     * @param MailSetting $mailSetting
     * @return RedirectResponse
     */
    public function destroy(MailSetting $mailSetting): RedirectResponse
    {
        try {
            $mailSetting->delete();
            Session::flash('alert-success', "Deleted Successfully!");
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
