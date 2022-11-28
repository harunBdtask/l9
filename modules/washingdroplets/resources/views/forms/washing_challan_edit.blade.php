@extends('washingdroplets::layout')
@section('title', 'Washing Challan')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Washing Challan Edit</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">

                        @include('partials.response-message')
                        {!! Form::model($washing_challan, ['url' => 'washing-challan/'.$washing_challan->id, 'method' =>'PUT']) !!}
                        <div class="form-group">
                            <label for="print_wash_factory_id" class="col-sm-2 form-control form-control-sm-label">Washing
                                Factory</label>
                            <div class="col-sm-10">
                                {!! Form::select('print_wash_factory_id', $washing_factories ?? [], null, ['class' => 'form-control form-control-sm c-select', 'id' => 'print_wash_factory_id', 'placeholder' => 'Please select One']) !!}

                                @if($errors->has('print_wash_factory_id'))
                                    <span class="text-danger">{{ $errors->first('print_wash_factory_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bag" class="col-sm-2 form-control form-control-sm-label">Bag</label>
                            <div class="col-sm-10">
                                {!! Form::selectRange('bag', 1, 20,null, ['class' => 'form-control form-control-sm c-select', 'id' => 'bag', 'placeholder' => 'Select bag(s)']) !!}
                                @if($errors->has('bag'))
                                    <span class="text-danger">{{ $errors->first('bag') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group m-t-md">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit"
                                        class="btn white">{{ $washing_challan ? 'Update' : 'Create' }}</button>
                                <a class="btn white" href="{{ url('washing-challan-list') }}">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
