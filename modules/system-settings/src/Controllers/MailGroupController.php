<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Requests\MailGroupRequest;

class MailGroupController extends Controller
{
    public function index(Request $request)
    {
        $searchGroup = $request->get('search_group');
        $groups = MailGroup::query()
            ->when($searchGroup, function ($query) use ($searchGroup) {
                $query->where('name', 'LIKE', "%$searchGroup%");
            })
            ->paginate();
        $users = User::query()->pluck('screen_name', 'id');

        return view('system-settings::mail-group.create',
            compact('groups', 'users')
        );
    }

    /**
     * @param MailGroupRequest $request
     * @param MailGroup $mailGroup
     * @return RedirectResponse
     */
    public function store(MailGroupRequest $request, MailGroup $mailGroup): RedirectResponse
    {
        try {
            $mailGroup->fill($request->all())->save();
            Session::flash('alert-success', 'Successfully stored');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    /**
     * @param MailGroup $mailGroup
     * @return JsonResponse
     */
    public function edit(MailGroup $mailGroup): JsonResponse
    {
        return response()->json($mailGroup);
    }

    /**
     * @param MailGroup $mailGroup
     * @return RedirectResponse
     */
    public function destroy(MailGroup $mailGroup): RedirectResponse
    {
        try {
            $mailGroup->delete();
            Session::flash('alert-success', "Deleted Successfully!");
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
