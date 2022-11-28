<div class="flash-message print-delete">
    @foreach (['danger', 'warning', 'success', 'info', 'error'] as $msg)
        @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
        @endif
    @endforeach
</div>
