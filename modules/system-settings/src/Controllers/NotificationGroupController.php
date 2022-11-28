<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\NotificationGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Requests\NotificationGroupRequest;

class NotificationGroupController extends Controller
{
    public function index(Request $request)
    {
        $searchGroup = $request->get('search_group');
        $groups = NotificationGroup::query()
            ->when($searchGroup, function ($query) use ($searchGroup) {
                $query->where('name', 'LIKE', "%$searchGroup%");
            })
            ->paginate();
        $users = User::query()->pluck('screen_name', 'id');

        return view('system-settings::notification-group.create',
            compact('groups', 'users')
        );
    }

    /**
     * @param NotificationGroupRequest $request
     * @param NotificationGroup $notificationGroup
     * @return RedirectResponse
     */
    public function store(NotificationGroupRequest $request, NotificationGroup $notificationGroup): RedirectResponse
    {
        try {
            $notificationGroup->fill($request->all())->save();
            Session::flash('alert-success', 'Successfully stored');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    /**
     * @param NotificationGroup $notificationGroup
     * @return JsonResponse
     */
    public function edit(NotificationGroup $notificationGroup): JsonResponse
    {
        return response()->json($notificationGroup);
    }

    /**
     * @param NotificationGroup $notificationGroup
     * @return RedirectResponse
     */
    public function destroy(NotificationGroup $notificationGroup): RedirectResponse
    {
        try {
            $notificationGroup->delete();
            Session::flash('alert-success', "Deleted Successfully!");
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
