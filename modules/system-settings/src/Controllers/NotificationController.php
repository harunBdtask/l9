<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function unreadNotification($id)
    {
        DB::table('notifications')->where('id', $id)->update(['read_at' => now()]);

        return redirectBack();
    }

    public function allRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirectBack();
    }

    public function getNotificationDropdownView()
    {
        $html = view('partials.dropdown-notification-details')->render();
        $notification_count = count(auth()->user()->unreadNotifications);

        return response()->json([
            'status' => 'success',
            'html' => $html,
            'notification_count' => $notification_count,
        ]);
    }
}
