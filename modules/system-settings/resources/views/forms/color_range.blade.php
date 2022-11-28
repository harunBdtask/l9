@extends('skeleton::layout')
@section("title","Color Ranges")
@section('content')
<div class="padding">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="box form-colors">
				<div class="box-header">
					<h2>{{ $color_range ? 'Update Color Range' : 'New Color Range' }}</h2>
				</div>
				<div class="box-divider m-a-0"></div>
				<div class="box-body">
					<div class="flash-message">
						@foreach (['danger', 'warning', 'success', 'info'] as $msg)
						@if(Session::has('alert-' . $msg))
						<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
						@endif
						@endforeach
					</div>

					{!! Form::model($color_range, ['url' => $color_range ? 'color-ranges/'.$color_range->id : 'color-ranges', 'method' => $color_range ? 'PUT' : 'POST']) !!}
					<div class="form-group">
						<label for="name">Color Range</label>
                        {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write color range here']) !!}

                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
					</div>
					<div class="form-group m-t-md">
                        <button type="submit" class="btn btn-sm btn-success"><i
                                class="fa fa-save"></i> {{ $color_range ? 'Update' : 'Create' }}</button>
                        <a class="btn btn-sm btn-warning" href="{{ url('color-ranges') }}"><i
                                class="fa fa-remove"></i> Cancel</a>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
