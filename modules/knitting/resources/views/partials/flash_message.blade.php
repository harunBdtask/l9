@foreach (['danger', 'warning', 'success', 'info'] as $msg)
	@if($msg == $message_class)
		<p class="text-center alert alert-{{ $msg }}">{{ $message }}</p>
	@endif
@endforeach