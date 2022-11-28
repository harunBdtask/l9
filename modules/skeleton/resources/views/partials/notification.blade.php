@php
    if(\Auth::check() && \Auth::user()->profile_image == null){
        $imageHtml = asset('modules/skeleton/flatkit/assets/images/avatar2.png');
    } elseif (\Auth::check() && Storage::disk('public')->exists('profile_image/'.auth()->user()->profile_image)){
        $imageHtml = asset('storage/profile_image/'.auth()->user()->profile_image);
    } else{
        $imageHtml = asset('modules/skeleton/flatkit/assets/images/avatar2.png');
    }
@endphp

@forelse($notifications ?? [] as $key => $notification)
    <li class="list-group-item {{ $notification->read_at ? 'dark-white' : 'black'}} lt box-shadow-z0 b">
        <span class="pull-left m-r">
            <img src="{{ $imageHtml }}" alt="..." class="w-40 img-circle">
        </span>
        <span class="clear block">
            <a href="/notification/{{ $notification->id }}">
                {!! $notification->data['title'] !!}
            </a><br>
            <small class="text-muted">
                {{ $notification->created_at->diffForHumans() }}
                <em class="pull-right"> {{ $notification->read_at ? 'Seen' : 'Unseen'}} </em>
            </small>
      </span>
    </li>
@empty
    <li class="list-group-item dark-white text-color box-shadow-z0 b">
        <span class="clear block text-center"> You don't have any notification now ! </span>
    </li>
@endforelse
