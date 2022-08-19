<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Notifications\TestNotification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    public function index()
    {
        //
    }
    
    public function sendNotification() {

        $notificationData = [
            'url' => '/notification',
            'title' => 'Test notification title will be here',
            'job_no' => null,
            'body' => '',
            'module' => 'test',
            'task_id' => 4,
        ];
        
        $user = User::first();
        if ($user) {
            $user->notify(new TestNotification($notificationData));
        }
   
        dd('Task completed!');
    }

    public function getNotification()
    {
        $notifications = auth()->user()->notifications()->orderByDesc('created_at')->paginate(10);

        $response = [
            'data' => $notifications,
            'view' => view('notification-list', compact('notifications'))->render()
        ];

        return response()->json($response);
    }

    public function readNotification($id)
    {
        $notification = Notification::query()->find($id);
        $notification->markAsRead();
        return redirect($notification->data['url']);
    }

    public function showNotifications()
    {
        $data = array(
            'title' => get_phrases(['notification', 'view']),
            'content'   => 'notification',
        );
        return view('layouts.layouts', $data);
    }

    public function notificationPage()
    {
        return "Show page contents of notification url";
    }
}