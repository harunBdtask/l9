<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\NotificationGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\NotificationSetting;
use SkylarkSoft\GoRMG\SystemSettings\Requests\NotificationSettingRequest;

class NotificationSettingController extends Controller
{
    public function index(Request $request)
    {
        $searchType = $request->get('search_type');

        $settings = NotificationSetting::query()
            ->when($searchType, function ($query) use ($searchType) {
                $query->where('notification_type', $searchType);
            })
            ->paginate();
        $groups = NotificationGroup::query()->pluck('name', 'id');
        $types = NotificationSetting::NOTIFICATION_TYPE;

        return view('system-settings::notification-settings.create', compact('settings', 'groups', 'types'));
    }

    /**
     * @param NotificationSettingRequest $request
     * @param NotificationSetting $notificationSetting
     * @return RedirectResponse
     */
    public function store(NotificationSettingRequest $request, NotificationSetting $notificationSetting): RedirectResponse
    {
        try {
            $notificationSetting->fill($request->all())->save();
            Session::flash('alert-success', 'Successfully stored');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    /**
     * @param NotificationSetting $notificationSetting
     * @return JsonResponse
     */
    public function edit(NotificationSetting $notificationSetting): JsonResponse
    {
        return response()->json($notificationSetting);
    }


    /**
     * @param NotificationSetting $notificationSetting
     * @return RedirectResponse
     */
    public function destroy(NotificationSetting $notificationSetting): RedirectResponse
    {
        try {
            $notificationSetting->delete();
            Session::flash('alert-success', "Deleted Successfully!");
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
