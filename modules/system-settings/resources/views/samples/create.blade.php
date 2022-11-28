@extends('skeleton::layout')
@section("title","Incoterm")


@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Garments Sample</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>

                {!! Form::model($sample, ['url' => $sample ? 'garments-sample/' . $sample->id : 'garments-sample', 'method' => $sample ? 'put' : 'post']) !!}

                <div class="row m-t">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="factory_id">Company</label>
                            {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'factory_id']) !!}
                            @error('factory_id')
                            <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="buyer_id">Buyer</label>
                            {!! Form::select('buyer_id[]', $buyers, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'buyer_id', 'multiple']) !!}
                            @error('buyer_id')
                            <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Sample Type</label>
                            {!! Form::select('type', \SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample::TYPES, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'type']) !!}
                            @error('type')
                            <span class="text-danger">{{ $errors->first('type') }}</span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="row m-t">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Sample Name</label>
                            {!! Form::text('name', null, ['class' => 'form-control form-control-sm']) !!}
                            @error('name')
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Sample Status</label>
                            {!! Form::select('status', \SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample::STATUSES, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'status']) !!}
                            @error('status')
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row m-t">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ $sample ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection

@push('script-head')
        <script>

            $(function () {
                $(document).on('change', '#factory_id', function () {
                    const factoryId = $(this).val();
                    $('#buyer_id').val('').empty();
                    console.log(factoryId);
                    if (!factoryId) throw 'Select Factory';
                    axios.get(`/buyers-for-factory/${factoryId}`).then(res => {
                        $('#buyer_id').html(res.data).select2();
                    })
                })
            });




            {{--$(document).on('click', '.edit', function () {--}}
            {{--    let id = $(this).data('id');--}}
            {{--    $.ajax({--}}
            {{--        method: 'get',--}}
            {{--        url: '{{ url('incoterms') }}/' + id,--}}
            {{--        success: function (result) {--}}
            {{--            $('#form').attr('action', `incoterms/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);--}}
            {{--            $('#incoterm').val(result.incoterm);--}}
            {{--            $('#submit').html(`<i class="fa fa-save"></i> Update`);--}}
            {{--        },--}}
            {{--        error: function (xhr) {--}}
            {{--            console.log(xhr)--}}
            {{--        }--}}
            {{--    })--}}
            {{--})--}}
        </script>
@endpush
