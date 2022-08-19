@php
    $imageHtml = asset('assets/dist/img/avatar.png')
@endphp

@forelse($notifications ?? [] as $key => $notification)
    <div class="notification-list">
        <div class="media new {{ $notification->read_at ? '' : 'bg-secondary text-white'}}">
            <div class="img-user"><img src="{{ $imageHtml }}" alt="..."></div>
            <div class="media-body">
                <a href="/notification/{{ $notification->id }}">
                    <h6>{!! $notification->data['title'] !!}</h6>
                </a>
                <small class="text-muted">
                    {{ $notification->created_at->diffForHumans() }}
                    <em class="pull-right"> {{ $notification->read_at ? 'Seen' : 'Unseen'}} </em>
                </small>
            </div>
        </div>
    </div>
@empty
    <div class="notification-list">
        <span class="clear block text-center"> You don't have any notification now ! </span>
    </div>
@endforelse
