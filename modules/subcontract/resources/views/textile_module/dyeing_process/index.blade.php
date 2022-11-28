@extends('subcontract::layout')
@section("title","Sub Dyeing Batch")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Dyeing Batches</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('subcontract/dyeing-process/batch-entry/create') }}"
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
                                <th>Batch Unique Id</th>
                                <th>Party</th>
                                <th>Order No</th>
                                <th>Batch No</th>
                                <th>Fabric Color</th>
                                <th>Batch Weight</th>
                                <th>Batch Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/dyeing-process/batch-entry', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('batch_uid', request('batch_uid') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('party_id', $parties ?? [], request('party_id') ?? null,[
                                        'class'=>'text-center select2-input',
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('order_no', request('order_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('batch_no', request('batch_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('fabric_color', request('fabric_color') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('batch_weight', request('batch_weight') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('batch_date', request('batch_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
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
                                <td colspan="10">&nbsp;</td>
                            </tr>
                            @foreach ($dyeingBatches as $batch)
                                {{--@continue(request('order_no') && !in_array(request('order_no'), $batch->order_nos))--}}

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $batch->factory->factory_name }}</td>
                                    <td>{{ $batch->batch_uid }}</td>
                                    <td>{{ $batch->supplier->name  }}</td>
                                    <td>{{ collect($batch->order_nos)->implode(', ') }}</td>
                                    <td>{{ $batch->batch_no }}</td>
                                    <td>{{ $batch->fabricColor->name }}</td>
                                    <td>{{ $batch->total_batch_weight }}</td>
                                    <td>{{ $batch->batch_date }}</td>
                                    <td>
                                        <button class="btn btn-xs btn-primary"
                                                id="buyerRateButton"
                                                data-id="{{ $batch->id }}"
                                                data-toggle="modal"
                                                data-target="#buyerRateModal">
                                            <em class="fa fa-money"></em>
                                        </button>

                                        @permission('permission_of_batch_entry_edit')
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('subcontract/dyeing-process/batch-entry/create?id=' . $batch->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        @endpermission

                                        @permission('permission_of_batch_entry_view')
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('subcontract/dyeing-process/batch-entry/view/'.$batch->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        @endpermission

                                        @permission('permission_of_batch_entry_delete')
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/dyeing-process/batch-entry/' . $batch->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                        @endpermission

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $dyeingBatches->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{--            Buyer Rate Modal--}}
            <div class="modal fade" id="buyerRateModal" tabindex="-1" role="dialog"
                 aria-labelledby="buyerRateModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Buyer Rate</h5>
                        </div>
                        <form id="buyerRateForm" method="post">
                            <div class="modal-body" style="padding: 0px;">

                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label style="margin-bottom: -2.5rem;">Dia Type</label>
                                                    <select class="form-control form-control-sm select2-input"
                                                            name="dia_type"
                                                            id="dia_type">
                                                        <option option="">Select</option>
                                                        @foreach($diaTypes as $diaType)
                                                            <option
                                                                value="{{ $diaType['id'] }}">{{ $diaType['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label style="margin-bottom: -2.5rem;">Rate</label>
                                                    <input type="text"
                                                           class="form-control-sm form-control"
                                                           name="buyer_rate"
                                                           id="buyer_rate">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{--            Buyer Rate Modal--}}

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            let id;
            $(document).on('click', '#buyerRateButton', function () {
                id = $(this).data('id');
                $.ajax({
                    "url": `/subcontract/dyeing-process/batch-entry/buyer-rate/${id}`,
                    "type": 'get',
                    "dataType": 'json',
                    success(response) {
                        $("#buyer_rate").val(response.data.buyer_rate);
                    },
                    error(err) {
                        console.log(err);
                    }
                });
            });

            $(document).on('submit', '#buyerRateForm', function (e) {
                e.preventDefault();
                const data = $(this).serializeArray();

                $.ajax({
                    "url": `/subcontract/dyeing-process/batch-entry/buyer-rate/${id}`,
                    "type": 'patch',
                    "dataType": 'json',
                    "data": data,
                    success(response) {
                        if (response.status === 201) {
                            toastr.success("Buyer rate updated successfully!");
                        }
                        $("#buyerRateModal").modal('hide');
                    },
                    error(err) {
                        console.log(err);
                    }
                });
            });
        });

    </script>
@endsection
