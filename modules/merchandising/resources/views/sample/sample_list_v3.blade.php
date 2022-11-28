@extends('skeleton::layout')
@section("title","Sample")
@push('style')

    <style>

        /*.table-header {*/
        /*    background: #93dcf9;*/
        /*}*/

        .table > thead > tr > th,
        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th {
            border: 1px solid #ddd;
        }

        .table > tbody > tr {
            height: 35px !important;
        }

        .table thead tr:nth-child(even) {
            background: #f9f9f9 !important;
        }

        .data-count {
            padding: 0px;
            text-align: right;
            font-size: 14px;
        }

        .reportTableCustom tbody tr td {
            padding-left: 3px;
            padding-right: 3px;
        }

        .search-select {
            border-radius: 0px;
            height: 38px;
            width: 150px !important;
            background: #fff;
            border-color: rgba(120, 130, 140, 0.2);
        }

        .in-print {
            display: none !important;
        }

        @media print {
            @page {
                size: a4 portrait !important;
                margin-top: -7mm;
            }

            .reportTable th,
            .reportTable td {
                border: 1px solid #000;
            }

            .reportTable {
                margin-bottom: 1rem;
                width: 100%;
                max-width: 100%;
                font-size: 16px !important;
                border-collapse: collapse;
            }

            .in-print {
                display: block !important;
                margin-top: -15px !important;
            }

            .print-delete {
                display: none !important;
            }
        }
    </style>

@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_sample_view') || Session::get('user_role') == 'super-admin'|| Session::get('user_role') == 'admin')
            <div class="box knit-card">
                <div class="box-header">
                    <div class="row in-print" style="display: none">
                        <h2 class="text-center">{{groupName()}}</h2>
                        <h3 class="text-center m-t-1">Unit: {{factoryName()}}</h3>
                        <h4 class="text-center m-t-1">{{factoryAddress()}}</h4>
                        <h2 class="text-center m-t-1 m-b-1">Sample List</h2>
                    </div>
                    <div class="row print-delete">
                        <div class="col-md-6">
                            <h2>Sample list</h2>
                        </div>
                        <div class="col-md-6" align="right">
                            <ul style="padding: 0px; margin: 0px">
                                <li style="list-style: none;display: inline-block"><a class="hidden-print btn btn-xs" title="Print this document" id="print"><i class=" fa fa-print"></i>&nbsp;Print</a></li>
                                <li style="list-style: none;display: inline-block"><a href="{{url('/sampleGetPdf')}}" class="hidden-print btn btn-xs" title="Download this pdf"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t">
                    <div class="col-md-3 print-delete">
                        @if(Session::has('permission_of_sample_add') || Session::get('user_role') == 'super-admin')
                            <a href="{{url('sample/create')}}" class="btn btn-sm white m-b add-new-btn">
                                <i class="glyphicon glyphicon-plus"></i> Add Sample
                            </a>
                        @endif
                    </div>
                    <div class="col-md-6 pull-right print-delete">
                        <form action="{{url('sample-search')}}" method="GET">
                            <div class="input-group">
                                @php
                                    $query_string = '';
                                    $column_name = '';
                                        if(old('query_string')){
                                            $query_string = old('query_string');
                                            $column_name = old('column_name');
                                        }
                                        if( request()->get('query_string')){
                                            $query_string =  request()->get('query_string');
                                            $column_name = request()->get('column_name');
                                        }
                                @endphp

                                <div class="input-group-btn">
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <select name="column_name" class="search-select form-control form-control-sm" style="margin-bottom: 7%">
                                                <option value="">Select Column</option>
                                                <option value="buyer" {{$column_name == 'buyer' ? 'selected="selected"' : '' }}>
                                                    Buyer
                                                </option>
                                                <option value="agent" {{$column_name == 'agent' ? 'selected="selected"' : '' }}>
                                                    Buying Agent
                                                </option>
                                                <option value="season" {{$column_name == 'season' ? 'selected="selected"' : '' }}>
                                                    Season
                                                </option>
                                                <option value="ref_no" {{$column_name == 'ref_no' ? 'selected="selected"' : '' }}>
                                                    Ref. No
                                                </option>
                                                <option value="dealing_merchant" {{$column_name == 'dealing_merchant' ? 'selected="selected"' : '' }}>
                                                    Dealing Merchant
                                                </option>
                                                <option value="team_lead" {{$column_name == 'team_lead' ? 'selected="selected"' : '' }}>
                                                    Team Lead
                                                </option>
                                                <option value="receive_date" {{$column_name == 'receive_date' ? 'selected="selected"' : '' }}>
                                                    Receive Date
                                                </option>
                                            </select>
                                        </div>
                                        <input name="query_string" type="text" class="form-control form-control-sm"
                                               value="{{$query_string}}" style="width: 50%">
                                        <button type="submit" class="btn btn-sm white m-b button-class"
                                                style="border-radius: 0px">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br class="print-delete">
                    <br class="print-delete">
                    <div class="flash-message print-delete">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>
                    <hr class="print-delete">
                    <p class="data-count print-delete">{{ $sample_lists->firstItem() }} to {{ $sample_lists->lastItem() }} of
                        total {{$sample_lists->total()}} entries</p>
                    <div class="table-responsive" style="min-height: 300px">
                        <table class="reportTable reportTableCustom" id="tableSample">
                            <thead>
                            <tr class="table-header">
                                <th>Buyer</th>
                                <th>Buying Agent</th>
                                <th>Season</th>
                                <th>Ref. No</th>
                                <th>Dealing Merchant</th>
                                <th>Team Lead</th>
                                <th>Receive Date</th>
                                {{--<th>Details</th>--}}
                                <th class="print-delete">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sample_lists->getCollection() as $sample_list)
                                <tr>
                                    <td>{{$sample_list->buyer ? $sample_list->buyer->name : 'N/A' }}</td>
                                    <td>{{$sample_list->agent ? $sample_list->agent->buying_agent_name : 'N/A'}}</td>
                                    <td>{{$sample_list->season}}</td>
                                    <td>{{$sample_list->sample_ref_no ? $sample_list->sample_ref_no : 'N/A' }}</td>
                                    <td>{{isset($sample_list->dealingMerchant->first_name) && isset($sample_list->dealingMerchant->last_name) ? $sample_list->dealingMerchant->first_name .' '.$sample_list->dealingMerchant->last_name : 'N/A'}}</td>
                                    <td>{{$sample_list->teamLead ? $sample_list->teamLead->first_name : '' }}</td>
                                    <td>{{date('d M Y',strtotime($sample_list->receive_date))}}</td>
                                    {{--<td>--}}
                                    {{----}}
                                    {{--</td>--}}
                                    <td class="print-delete">
                                        <div class="dropdown inline">
                                            <div class="dropdown inline">
                                                <button class="btn btn-xs white dropdown-toggle" data-toggle="dropdown"
                                                        aria-expanded="false">Action
                                                </button>
                                                <div class="dropdown-menu pull-right">
                                                    <a href="javascript:;" class="dropdown-item sample-details"
                                                       sample_id="{{$sample_list->id}}" data-toggle="modal"
                                                       data-target="#sample-details">Details</a>

                                                    <a class="dropdown-item" target="_blank"
                                                       href="{{url('download-work-sample-file/'. $sample_list->id)}}">Download
                                                        Sample File</a>


                                                    <div class="dropdown-divider"></div>
                                                    @if(Session::has('permission_of_sample_edit') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                                        <a class="dropdown-item"
                                                           href="{{url('sample/' . $sample_list->id . '/edit')}}">Edit</a>
                                                    @endif
                                                    @if(Session::has('permission_of_sample_delete') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                                        <a class="dropdown-item"
                                                           href="{{url('sample/' . $sample_list->id . '/delete')}}"
                                                           onclick="return confirm('Are you sure you want to Delete?');">Delete</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No data found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center print-delete">{{$sample_lists->appends($_GET)->links() }}</div>
                        {{--                        <div class="text-center print-delete">{{$sample_lists->render()}}</div>--}}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Quotation Details</h4>
                </div>
                <div class="modal-body quotation-append-here">
                    <p>Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approval" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Buyer Approval</h4>
                </div>
                {!! Form::open(['url' => '', 'method' => 'POST','files' => true,'id'=>'buyer_approval_form']) !!}
                {!! Form::hidden('sample_id','',['class'=>'popup_sample_id']) !!}
                <div class="modal-body">
                    <div class="status"></div>
                    <div class="col-md-8 col-md-offset-2">
                        <div class="form-group">
                            <label for="status" class="col-sm-4 form-control form-control-sm-label">Approval Status</label>
                            <div class="col-sm-8">
                                {!! Form::select('status', ['10'=>'Approve','11'=>'Reject'],null, ['class' => 'form-control form-control-sm', 'id' => 'status', 'placeholder' => 'Select Approval Status','required'=>true]) !!}
                                @if($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('department_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarks" class="col-sm-4 form-control form-control-sm-label">Remarks</label>
                            <div class="col-sm-8">
                                {!! Form::textarea('remarks',null, ['class' => 'form-control form-control-sm', 'id' => 'remarks', 'placeholder' => 'Remark','rows'=>3]) !!}
                                @if($errors->has('remarks'))
                                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn white change_buyer_status">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="sendSample" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Sample Send Entry</h4>
                </div>
                {!! Form::open(['url' => '', 'method' => 'POST','files' => true,'id'=>'send_sample_form']) !!}
                {!! Form::hidden('sample_id','',['class'=>'send_sample_id']) !!}
                <div class="modal-body">
                    <div class="status"></div>
                    <div class="col-md-8 col-md-offset-2">
                        <div class="form-group">
                            <label for="send_date" class="col-sm-4 form-control form-control-sm-label">Date</label>
                            <div class="col-sm-8">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    {!! Form::text('send_date',null, ['class' => 'form-control form-control-sm datepicker', 'id' => 'send_date', 'placeholder' => 'mm/dd/yyyy']) !!}
                                </div>
                                @if($errors->has('send_date'))
                                    <span class="text-danger">{{ $errors->first('send_date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-sm-4 form-control form-control-sm-label">Address</label>
                            <div class="col-sm-8">
                                {!! Form::textarea('address',null, ['class' => 'form-control form-control-sm', 'id' => 'address', 'placeholder' => 'Address','rows'=>1]) !!}
                                @if($errors->has('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarks" class="col-sm-4 form-control form-control-sm-label">Remarks</label>
                            <div class="col-sm-8">
                                {!! Form::textarea('remarks',null, ['class' => 'form-control form-control-sm', 'id' => 'remarks', 'placeholder' => 'Remark','rows'=>3]) !!}
                                @if($errors->has('remarks'))
                                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn white sample_send_status">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal fade" id="sample-details" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sample Details</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>Item</td>
                            <td>Item Description</td>
                            <td>Fabric Description</td>
                            {{--<td>Fabrication</td>--}}
                            <td>GSM</td>
                            <td>Unit Price</td>
                        </tr>
                        </thead>
                        <tbody class="details-here">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(function () {
            $('body').on('click', '#print', function () {
                $('.print-delete').hide();
                $('#tableSample').removeClass('table-responsive');
                window.print();
                $('.print-delete').show();
            });
            /* Quotation details view popup */
            $('.get-quotation').on('click', function () {
                var sample_id = $(this).attr('sample_id');
                var artwork_id = $(this).attr('artwork_id');
                $('.quotation-append-here').html('<p>Loading...</p>');
                $.ajax({
                    url: 'price-quotation/view-quotation',
                    type: 'GET',
                    data: {sample_id: sample_id, artwork_id: artwork_id},
                    success: function (data) {
                        $('.quotation-append-here').html(data);
                    }
                });
            });

            /* Buyer Approval Status Popup */

            $('.buyer_approval').on('click', function () {
                var sample_id = $(this).attr('sample_id');
                $('.popup_sample_id').val(sample_id);
            });

            $('.change_buyer_status').on('click', function () {
                var data = $('#buyer_approval_form').serialize();
                $.ajax({
                    type: "post",
                    url: '{{url('sample-development/buyer-approval-status')}}',
                    data: data,
                    success: function (msg) {
                        $('.status').html(msg);
                        $('#buyer_approval_form').trigger("reset");
                        setTimeout(function () {
                            $('#approval').modal('hide');
                        }, 2000);
                    }
                });
                return false;
            });

            /* Buyer Approval Status Popup */

            $('.send-sample').on('click', function () {
                var sample_id = $(this).attr('sample_id');
                $('.send_sample_id').val(sample_id);
            });

            $('.sample_send_status').on('click', function () {
                var data = $('#send_sample_form').serialize();
                $.ajax({
                    type: "post",
                    url: 'sample-development/sample-send',
                    data: data,
                    success: function (msg) {
                        $('.status').html(msg);
                        $('#send_sample_form').trigger("reset");
                        setTimeout(function () {
                            $('#sendSample').modal('hide');
                        }, 2000);
                    }
                });
                return false;
            });

            /* Preview Order */

            $('.preview_order').on('click', function () {
                var sample_id = $(this).attr('sample_id');
                $.ajax({
                    url: 'order/preview-order',
                    type: 'GET',
                    data: {sample_id: sample_id},
                    success: function (data) {
                        $('.order-details-append-here').html(data);
                    }
                });
            });


            /* Sample details Popup */

            $('.sample-details').on('click', function () {
                var sample_id = $(this).attr('sample_id');
                $.ajax({
                    type: "get",
                    url: 'sample-details' + '/' + sample_id,
                    success: function (data) {
                        $('.details-here').html(data);
                    }
                });
            });


        });
    </script>
@endpush
