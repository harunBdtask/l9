<!-- dropdown -->
<div class="dropdown-menu pull-right w-xl animated fadeInUp no-bg no-border no-shadow">
    <div class="scrollable notification-list-dropdown" style="max-height: 250px">
        @if(count(auth()->user()->unreadNotifications ?? []) > 0 )
            <ul class="list-group list-group-gap m-a-0">
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <li class="list-group-item black lt box-shadow-z0 b">
                        <a href="{{ url('notification-read/'.$notification->id) }}" class="pull-right m-r fa fa-remove"></a>
                        <span class="clear block">
                            <a class="text-primary">{{$notification->data['message'].' '.$notification->data['information'] .' By '.$notification->data['user_name'] }}</a>
                            <br>
                            <small class="text-muted">{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</small>
                        </span>
                    </li>
                @endforeach
            </ul>
            <span class="clear block black text-center">
                <a href="{{url('all-notification-read')}}"class="text-primary text-small m-r-1">Clear All Notifications</a>
            </span>
        @else
            <ul class="list-group list-group-gap m-a-0">
                <li class="list-group-item black lt box-shadow-z0 b">
                    <a class="pull-right m-r fa fa-remove"></a>
                    <span class="clear block">
                        <a class="text-primary">You don't have any notification now</a>
                        <br>
                        <small class="text-muted"></small>
                    </span>
                </li>
            </ul>
        @endif
    </div>
</div>
<!-- / dropdown -->