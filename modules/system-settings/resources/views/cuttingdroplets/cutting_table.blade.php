@extends('skeleton::layout')
@section('title', 'Cutting Table')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $table ? 'Update Cutting Table' : 'New Cutting Table' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body form-colors">
                        {!! Form::model($table, ['url' => $table ? 'cutting-tables/'.$table->id : 'cutting-tables', 'method' => $table ? 'PUT' : 'POST']) !!}

                        <div class="form-group">
                            <label for="floor_id">Cutting
                                Floor</label>
                            {!! Form::select('cutting_floor_id', $floors, null, ['class' => 'floor-select form-control form-control-sm select2-input', 'id' => 'cutting_floor_id', 'placeholder' => 'Select a Floor']) !!}

                            @if($errors->has('cutting_floor_id'))
                                <span class="text-danger">{{ $errors->first('cutting_floor_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="name">Cutting Table
                                No.</label>
                            {!! Form::text('table_no', null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'table_no', 'placeholder' => 'Write table\'s no here']) !!}

                            @if($errors->has('table_no'))
                                <span class="text-danger">{{ $errors->first('table_no') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn btn-sm btn-success"><i
                                    class="fa fa-save"></i> {{ $table ? 'Update' : 'Create' }}</button>
                            <button type="button" class="btn btn-sm btn-warning"><a
                                    href="{{ url('cutting-tables') }}"><i class="fa fa-remove"></i> Cancel</a>
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        if(old('floor_id') && !$errors->has('floor_id')){
          $floor_id = old('floor_id');
        }elseif($table){
          $floor_id = $table->floor_id;
        }else{
          $floor_id = '';
        }
    @endphp

@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).on('change', '.floor-select', function () {
            $("#line-dropdown").empty();
            var floor_id = $('.floor-select').val();
            if (floor_id) {
                $.ajax({
                    type: 'GET',
                    url: '/get-lines/' + floor_id,
                    success: function (response) {

                        if (Object.keys(response).length > 0) {
                            var lineDropdown;
                            $.each(response, function (index, val) {
                                lineDropdown += '<option  value="' + index + '">' + val + '</option>';
                                $("#line-dropdown").html(lineDropdown);
                            });
                        }
                    }
                });
            }
        });

    </script>
@endsection
