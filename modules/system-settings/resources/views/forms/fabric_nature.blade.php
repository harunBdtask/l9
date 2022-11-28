@extends('skeleton::layout')
@section("title","Fabric Natures")
@section('content')
	<div class="padding">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="box" >
					<div class="box-header">
						<h2>{{ $fabric_nature ? 'Update Fabric Nature' : 'New Fabric Nature' }}</h2>
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

						{!! Form::model($fabric_nature, ['url' => $fabric_nature ? 'fabric-natures/'.$fabric_nature->id : 'fabric-natures', 'method' => $fabric_nature ? 'PUT' : 'POST']) !!}
						<div class="form-group">
							<label for="name">Fabric Nature</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write fabric type here']) !!}

                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
						</div>
						<div class="form-group m-t-md">
                            <button type="submit" class="btn btn-sm btn-success">{{ $fabric_nature ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning" href="{{ url('fabric-natures') }}">Cancel</a>
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
