@extends('subcontract::layout')
@section("title","Sub Grey Store Material Fabric Receive")
@section('content')
    <style>
        tr:hover {
            background-color: rgb(148, 218, 251);
        }

        .card {
            cursor: pointer !important;
        }
    </style>

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Grey Store Material Fabric Receive</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="/subcontract/material-fabric-receive/create" class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create</a>
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
                                <th>Grey Store</th>
                                <th>Receive Basis</th>
                                <th>Order No</th>
                                <th>Challan No</th>
                                <th>Challan Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/material-fabric-receive', 'method'=>'GET']) !!}
                            <tr>
                                <td></td>
                                <td>
                                    {!! Form::select('factory', $factories, request('factory'), ['class'=>'text-center select2-input']) !!}
                                </td>
                                <td>
                                    {!! Form::select('supplier', $supplier ?? [],request('supplier'),['class'=>'text-center select2-input'] ) !!}
                                </td>
                                <td>
                                    {!! Form::select('store', $stores, request('store'), ['class'=>'text-center select2-input']) !!}
                                </td>
                                <td>
                                    {!! Form::select('receive_basis', $receiveBasises, request('receive_basis'), ['class'=>'text-center select2-input']) !!}
                                </td>
                                <td>
                                    <input style="width: 90%;border: 1px solid #cecece;" type="text"
                                           class="text-center" placeholder="Write" name="order_no"
                                           value="{{ request('order_no') }}"/>
                                </td>
                                <td>
                                    <input style="width: 90%;border: 1px solid #cecece;" type="text"
                                           class="text-center" placeholder="Write" name="challan_no"
                                           value="{{ request('challan_no') }}"/>
                                </td>
                                <td>
                                    <input style="width: 90%;border: 1px solid #cecece;" type="date"
                                           class="text-center" placeholder="Write" name="challan_date"
                                           value="{{ request('challan_date') }}"/>
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            <tr>
                                <td colspan="9">&nbsp;</td>
                            </tr>
                            @foreach($subGreyReceives as $receive)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $receive->factory->factory_name }}</td>
                                    <td>{{ $receive->supplier->name }}</td>
                                    <td>{{ $receive->greyStore->name }}</td>
                                    <td>{{ $receive->receive_basis_value }}</td>
                                    <td>{{ (collect($receive->challanOrders)->pluck('textileOrder.order_no')->implode(', '))??null }}</td>
                                    <td>{{ $receive->challan_no }}</td>
                                    <td>{{ $receive->challan_date ? \Carbon\Carbon::make($receive->challan_date)->toFormattedDateString() : null }}</td>
                                    <td>
                                        @if(isset($variableSetting->variable_details['barcode_enable']) &&
                                               $variableSetting->variable_details['barcode_enable'])
                                            <a class="btn btn-xs teal" title="Barcode View"
                                               href="/subcontract/material-fabric-receive/barcode/view/{{$receive->id}}"
                                            >
                                                <em class="fa fa-qrcode"></em>
                                            </a>
                                            <button style="margin-left: 2px;"
                                                    type="button"
                                                    class="btn btn-xs btn-primary show-modal"
                                                    title="Generate Barcode"
                                                    id="generate_barcode"
                                                    data-toggle="modal"
                                                    data-target="#barcodeQtyModal"
                                                    ui-toggle-class="flip-x"
                                                    data-id="{{ $receive->id }}"
                                                    ui-target="#animate">
                                                <em class="fa fa-plus"></em>
                                            </button>
                                        @endif
                                        <a class="btn btn-warning btn-xs" title="Return"
                                           href="/subcontract/material-fabric-receive/create?id={{$receive->id}}&mode=return">
                                            <em class="fa fa-retweet"></em>
                                        </a>
                                        @permission('permission_of_material_receive_edit')
                                        <a class="btn btn-info btn-xs" type="button" title="Edit"
                                           href="/subcontract/material-fabric-receive/create?id={{$receive->id}}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        @endpermission
                                        @permission('permission_of_material_receive_view')
                                        <a class="btn btn-success btn-xs" type="button"
                                           title="View"
                                           href="{{ url('subcontract/material-fabric-receive/view/'.$receive->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        @endpermission
                                        @permission('permission_of_material_receive_delete')
                                        <button style="margin-left: 2px;"
                                                type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/material-fabric-receive/'.$receive->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                        @endpermission
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $subGreyReceives->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="barcodeQtyModal" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title" id="exampleModalLongTitle">Generate Barcode</h5>
                        </div>
                        <div class="modal-body" style="max-height : 350px; overflow-x: scroll">

                            <div class="row">
                                <div class="col-md-12" id="receive_details">

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="barcode_modal">
                                Close
                            </button>
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
            $('#party').select2({
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
                            },
                        };
                    },
                    cache: true,
                    delay: 150,
                },
                placeholder: 'Search',
                allowClear: true,
            });

            $(document).on('click', '#generate_barcode', function () {
                let receiveId = $(this).attr('data-id');
                let receiveHead = $('#receive_details');

                $.ajax({
                    url: `/subcontract/material-fabric-receive/barcode/create/${receiveId}`,
                    dataType: 'html',
                    success: function (response) {
                        receiveHead.empty();
                        receiveHead.html(response);
                    }
                })
            });

            $(document).on('click', "#detail", function () {
                if ($(this).attr('aria-expanded') === 'true') {
                    let totalRoll = $(this).attr('data-total-roll');
                    let tbody = $(this).parent("div").find("#detail_table");
                    tbody.empty();
                    let totalRow = [];

                    for (let i = 0; i < totalRoll; i++) {
                        totalRow.push(`
                            <tr>
                                <td>Roll ${i + 1}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="barcode_qty[]" id="barcode_qty.${i}">
                                </td>
                            </tr>
                        `);
                    }
                    tbody.append(totalRow);
                }
            });

            $(document).on('submit', '#detail_form', function (event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    data: $(this).serializeArray(),
                    type: 'POST',
                    success: function (response) {
                        if (response.status === 201) {
                            $('#barcode_modal').click();
                        }
                    },
                    error: function (error) {
                        let errors = {...error.responseJSON.errors};

                        if (errors) {
                            toastr.error('Field is required');
                        }

                        if (errors?.barcode_qty) {
                            toastr.error(errors?.barcode_qty[0]);
                        }
                    }
                });
            });
        });
    </script>
@endsection
