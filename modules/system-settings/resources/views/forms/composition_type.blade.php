@extends('skeleton::layout')
@section("title","Composition Types")
@section('content')
	<div class="padding">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="box" >
					<div class="box-header">
						<h2>{{ $composition_type ? 'Update Composition Type' : 'New Composition Type' }}</h2>
					</div>
					<div class="box-divider m-a-0"></div>
					<div class="box-body form-colors"> 
						<div class="flash-message">
							@foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
								@endif
							@endforeach
						</div>

						{!! Form::model($composition_type, ['url' => $composition_type ? 'composition-types/'.$composition_type->id : 'composition-types', 'method' => $composition_type ? 'PUT' : 'POST']) !!}
						<div class="form-group">
							<label for="name">Composition Type</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write fabric type here']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
						</div>
						<div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm btn-success"><i
                                    class="fa fa-save"></i> {{ $composition_type ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning" href="{{ url('composition-types') }}"><i
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
