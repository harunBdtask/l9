<div class="row">
    <div class="col-md-12">
        <div class="flash-message">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <div
                        class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                @endif
            @endforeach
        </div>
    </div>
</div>