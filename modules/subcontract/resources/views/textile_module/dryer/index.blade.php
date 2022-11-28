@extends('subcontract::layout')
@section("title","Sub Dryer")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Dryer</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('/subcontract/dryer/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company</th>
                                <th>Party</th>
                                <th>Entry Basis</th>
                                <th>Batch/Order No</th>
                                <th>Color</th>
                                <th>Dyeing Unit</th>
                                <th>Production Date</th>
                                <th>M/C Name</th>
                                <th>Finish Qty</th>
                                <th>Shift</th>
                                <th>Loading Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/dryer', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('party_id', [], request('party_id'),[
                                      'class'=>'text-center form-control form-control-sm', 'id'=>'party_id'
                                  ]) !!}

                                </td>
                                <td>
                                    {!! Form::select('entry_basis', ['0'=>'Select','1'=>'BATCH', '2'=>'ORDER'], request('entry_basis'),[
                                      'class'=>'text-center form-control form-control-sm select2-input', 'id'=>'entry_basis'
                                  ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('order_batch_no', request('order_batch_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('color', request('color') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('dyeing_unit', $dyeingUnits ?? [], request('dyeing_unit'),[
                                      'class'=>'text-center form-control form-control-sm select2-input', 'id'=>'dyeing_unit'
                                  ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('production_date', request('production_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('machine',$machines ?? [], request('machine') ?? null, [
                                        'class'=>'text-center form-control form-control-sm select2-input'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    {!! Form::select('shift',$shifts ?? [], request('shift') ?? null, [
                                        'class'=>'text-center form-control form-control-sm select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('loading_time', request('loading_time') ?? null, [
                                        'class'=>'text-center form-control form-control-sm select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            <tr>
                                <td colspan="13">&nbsp;</td>
                            </tr>
                            @foreach ($subDryers as $dryer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dryer->factory->factory_name }}</td>
                                    <td>{{ $dryer->supplier->name  }}</td>
                                    <td>{{ $dryer->entry_basis_value }}</td>
                                    <td>{{ $dryer->batch_no ?? $dryer->order_no }}</td>
                                    <td>
                                        {{
                                            $dryer->subDryerDetails
                                                ->pluck('color.name')->unique()->implode(', ')
                                        }}
                                    </td>
                                    <td>{{ $dryer->subDyeingUnit->name }}</td>
                                    <td>{{ $dryer->production_date }}</td>
                                    <td>{{ $dryer->machine->name }}</td>
                                    <td>{{ $dryer->subDryerDetails->sum('finish_qty') }}</td>
                                    <td>{{ $dryer->shift->shift_name }}</td>
                                    <td>{{ $dryer->loading_time }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('subcontract/dryer/create?id=' . $dryer->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('subcontract/dryer/view/'.$dryer->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/dryer/' . $dryer->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#party_id').select2({
                ajax: {
                    url: "/subcontract/api/v1/textile-parties/select-search",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data,
                            pagination: {
                                more: false
                            }
                        };
                    },
                    cache: true,
                    delay: 150,
                },
                placeholder: 'Search',
                allowClear: true,
            });

        });

    </script>
@endsection
