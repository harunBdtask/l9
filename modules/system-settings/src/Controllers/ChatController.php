<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;
use SkylarkSoft\GoRMG\SystemSettings\Models\Chat;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ChatController extends Controller
{
    public function getUserMessage()
    {
        $userId = \request('userId');
        $datas = Chat::where('chat_id', $this->getChannelId($userId))->limit(50)->get();
        $html = '';
        foreach ($datas as $data) {
            if ($data->sender_id == auth()->user()->id) {
                $imageHtml = '';
                if (auth()->user()->profile_image == null) {
                    $imageHtml = '<img src=' . asset('flatkit/assets/images/avatar2.png') . ' class="img-circle w-full" >';
                } elseif (Storage::disk('public')->exists('profile_image/' . auth()->user()->profile_image)) {
                    $imageHtml = '<img src=' . asset('storage/profile_image/' . auth()->user()->profile_image) . ' class=" w-full img-circle" >';
                } else {
                    $imageHtml = "<img src=" . asset('flatkit/assets/images/avatar2.png') . " class='w-full img-circle' >";
                }
                $html .= '<div data-message="' . $data->id . '" class="m-b messages">';
                $html .= '<a class="pull-left w-40 m-r-sm">';
                $html .= ' ' . $imageHtml . '
            </a>';
                $html .= '<div class="clear"><div>';
                $html .= '<div class="p-a p-y-sm dark-white inline r">';
                $html .= $data->message . '</div>';
                $html .= '</div>';
                $html .= '<div class="text-muted text-xs m-t-xs">';
                $html .= '<i class="fa fa-ok text-success"></i> ' . $data->created_at->diffForHumans() . '</div>';
                $html .= '</div></div>';
            } else {
                $this->setAllUnreadMessageSeen($data->id);
                $user = User::find($data->sender_id);
                $imageHtml = '';
                if ($user->profile_image == null) {
                    $imageHtml = '<img src=' . asset('flatkit/assets/images/avatar2.png') . ' class="img-circle w-full" >';
                } elseif (Storage::disk('public')->exists('profile_image/' . $user->profile_image)) {
                    $imageHtml = '<img src=' . asset('storage/profile_image/' . $user->profile_image) . ' class=" w-full img-circle" >';
                } else {
                    $imageHtml = "<img src=" . asset('flatkit/assets/images/avatar2.png') . " class='w-full img-circle' >";
                }
                $html .= '<div  data-message="' . $data->id . '" class="m-b messages" >';
                $html .= '<a class="pull-right w-40 m-l-sm">';
                $html .= ' ' . $imageHtml . '
            </a>';
                $html .= '<div class="clear text-right"><div>';
                $html .= '<div class="p-a p-y-sm info inline text-left r">';
                $html .= $data->message . '</div>';
                $html .= '</div>';
                $html .= '<div class="text-muted text-xs m-t-xs">';
                $html .= '<i class="fa fa-ok text-success"></i> ' . $data->created_at->diffForHumans() . '</div>';
                $html .= '</div></div>';
            }
        }
        echo $html;
        exit;
    }

    public function sendMessage(Request $request)
    {
        $chat = new Chat();
        $chat->message = $request->message;
        $chat->sender_id = auth()->user()->id;
        $chat->receiver_id = $request->receiver_id;
        $channel_id = $this->getChannelId($chat->receiver_id);
        $chat->chat_id = $channel_id;
        $chat->save();
        $pusher = new Pusher("5c0e022f1182afb8c365", "9d34182c209bca2e5891", "811755", ['cluster' => 'ap2']);
        if (auth()->user()->profile_image == null) {
            $imageHtml = asset('flatkit/assets/images/avatar2.png');
        } elseif (Storage::disk('public')->exists('profile_image/' . auth()->user()->profile_image)) {
            $imageHtml = asset('storage/profile_image/' . auth()->user()->profile_image);
        } else {
            $imageHtml = asset('flatkit/assets/images/avatar2.png');
        }
        $information = [
            'id' => $chat->id,
            'message' => $request->message,
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
            'image' => $imageHtml,
            'time' => now()->diffForHumans(),

        ];

        $pusher->trigger($this->getChannelId($request->receiver_id), 'my-event', ['message' => $information]);

        \Notification::send(User::findOrFail($request->receiver_id), new UserNotification('You have a message: ', '', auth()->user()->id));

        return response()->json(['status' => 'success']);
    }

    private function setAllUnreadMessageSeen($id)
    {
        return Chat::where('id', $id)->update(['receiver_seen_status' => 1]);
    }

    private function getChannelId($userId)
    {
        if (auth()->user()->id > $userId) {
            return implode('-', [$userId, auth()->user()->id]);
        } else {
            return implode('-', [auth()->user()->id, $userId]);
        }
    }

    public function chatDelete()
    {
        $id = \request('chat_id');
        $chat = Chat::where('id', $id)->get();
        if (count($chat) > 0) {
            Chat::find($id)->delete();
            $status = true;
        } else {
            $status = false;
        }

        return response(['message' => 'success', 'status' => $status]);
    }

    public function getUserUnreadChatCount()
    {
        $onlineUsers = User::where('status', true)
            ->whereDate('last_login', Carbon::today()->toDateString())
            ->get();
        $offlineUsers = User::where('status', false)
            ->get();
        \Cache::put('onlineUsers', $onlineUsers, 1440);
        \Cache::put('offlineUsers', $offlineUsers, 1440);

        $html = view('partials.chattListView', [
            'onlineUsers' => $onlineUsers,
            'offlineUsers' => $offlineUsers, ])->render();

        return response()->json(['html' => $html]);
    }

    public function getUserChatChannelIds()
    {
        $chat_channel_ids = Chat::where('receiver_id', userId())
            ->select('chat_id')
            ->distinct()
            ->pluck('chat_id');

        return response()->json([
            'status' => 'success',
            'channel_ids' => $chat_channel_ids,
        ]);
    }
}
