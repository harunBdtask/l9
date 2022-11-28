<div class="nav-fold">
    <a href="{{ url('account-system-settings') }}">
        @php
            if(\Auth::check() && \Auth::user()->profile_image == null){
                $imageHtml = asset('modules/skeleton/flatkit/assets/images/avatar2.png');
            } elseif (\Auth::check() && Storage::disk('public')->exists('profile_image/'.auth()->user()->profile_image)){
                $imageHtml = asset('storage/profile_image/'.auth()->user()->profile_image);
            } else{
                $imageHtml = asset('modules/skeleton/flatkit/assets/images/avatar2.png');
            }
        @endphp
        <span class="pull-left">
            <img src="{{ $imageHtml }}" alt="..." class="w-40 img-circle">
        </span>
        <span class="clear hidden-folded p-x">
            <span class="block _500">{{ \Auth::check() ? \Auth::user()->first_name.' '.\Auth::user()->last_name : '' }}</span>
            <small class="block text-muted">{{ \Auth::user()->address ?? '' }}</small>
        </span>
    </a>
</div>
