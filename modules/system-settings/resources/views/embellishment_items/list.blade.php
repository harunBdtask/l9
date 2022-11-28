@extends('skeleton::layout')
@section("title","Embellishment Items")
@section('styles')
    <style>
        .custom-control-label {
            padding: 0.165rem 0;
        }

        .custom-form-section {
            border-radius: 6px;
            /*padding: 13px 0;*/
        }

        .custom-field {
            width: 90%;
            border: 1px solid #cecece;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Embellishment Items</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div
                                    class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box form-colors">
                            <div class="box-header">
                                {!! Form::model($embellishment_item, ['url' => $embellishment_item ?
                        '/embellishment-items/'.$embellishment_item->id : '/embellishment-items', 'method' => $embellishment_item ?
                        'PUT' : 'POST', 'id' => 'embellishment-item-entry-form']) !!}
                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Embellishment Name</label>
                                    {!! Form::select('name', $embellishment_names ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' =>
                                    'name', 'placeholder'
                                    =>
                                    'Select here', 'required']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="type" class="custom-control-label">Embellishment Type</label>
                                    {!! Form::text('type', null, ['class' => 'form-control form-control-sm', 'id' => 'type', 'placeholder' =>
                                    'Write embellishment type here', 'required']) !!}
                                    @if($errors->has('type'))
                                        <span class="text-danger">{{ $errors->first('type') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="type" class="custom-control-label">Embellishment Tag</label>
                                    {!! Form::select('tag', $tags, null,['class' => 'form-control form-control-sm', 'id' => 'type', 'placeholder' =>
                                    'Select embellishment Tag here', 'required']) !!}
                                    @if($errors->has('type'))
                                        <span class="text-danger">{{ $errors->first('tag') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Submit
                                    </button>
                                    <a href="{{ url('/embellishment-items') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['url' => '/embellishment-items', 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th></th>
                                <td>
                                {!! Form::text('search_name', request()->search_name ?? null, ['class' => 'custom-field text-center',
                                'placeholder' => 'Search here']) !!}
                                </th>
                                <td>
                                {!! Form::text('search_type', request()->search_type ?? null, ['class' => 'custom-field text-center',
                                'placeholder' => 'Search here']) !!}
                                </th>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                        Search
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Embellishment Name</th>
                                <th>Embellishment Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$embellishment_items->getCollection()->isEmpty())
                                @foreach($embellishment_items->getCollection() as $embellishment_item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $embellishment_item->name }}</td>
                                        <td>{{ $embellishment_item->type }}</td>
                                        <td>
                                            <a href="{{url('embellishment-items/'.$embellishment_item->id.'/edit')}}"
                                               class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('embellishment-items/'.$embellishment_item->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" align="center">No Data</td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($embellishment_items->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $embellishment_items->appends(request()->except('page'))->links() }}
                                    </td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
