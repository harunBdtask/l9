@extends('subcontract::layout')
@section("title","Sub Dyeing Stentering")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Dyeing Stentering</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('subcontract/stenter/create') }}"
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
                                <th>Roll Qty</th>
                                <th>Fin. Qty</th>
                                <th>Dyeing Unit</th>
                                <th>Production Date</th>
                                <th>M/C Name</th>
                                <th>Shift</th>
                                <th>Loading Time</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/stenter', 'method'=>'GET']) !!}
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
                                <td></td>
                                <td>
                                    {!! Form::text('finish_qty', request('finish_qty') ?? null, [
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
                                <td colspan="14">&nbsp;</td>
                            </tr>
                            @foreach ($subDyeingStenterings as $subDyeingStentering)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subDyeingStentering->factory->factory_name }}</td>
                                    <td>{{ $subDyeingStentering->supplier->name  }}</td>
                                    <td>{{ $subDyeingStentering->entry_basis_value }}</td>
                                    <td>{{ $subDyeingStentering->batch_no ?? $subDyeingStentering->order_no }}</td>
                                    <td>
                                        {{
                                            $subDyeingStentering->subDyeingStenteringDetails
                                                ->pluck('color.name')->unique()->implode(', ')
                                        }}
                                    </td>
                                    <td>{{ $subDyeingStentering->subDyeingStenteringDetails->sum('fin_no_of_roll') }}</td>
{{--                                    <td>{{ $subDyeingStentering->subDyeingStenteringDetails->sum('finish_qty') }}</td>--}}
                                    <td>{{ $subDyeingStentering->subDyeingStenteringDetails->pluck('finish_qty')->join(', ') }}</td>
                                    <td>{{ $subDyeingStentering->subDyeingUnit->name }}</td>
                                    <td>{{ $subDyeingStentering->production_date }}</td>
                                    <td>{{ $subDyeingStentering->machine->name }}</td>
                                    <td>{{ $subDyeingStentering->shift->shift_name }}</td>
                                    <td>{{ $subDyeingStentering->loading_date }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('subcontract/stenter/create?id=' . $subDyeingStentering->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('subcontract/stenter/view/'.$subDyeingStentering->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/stenter/' . $subDyeingStentering->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $subDyeingStenterings->appends(request()->except('page'))->links() }}
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
                    processResults: function (response, params) {
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
