@extends('skeleton::layout')
@push('style')
    <style>
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
    </style>
@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_sample_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="box knit-card">
                <div class="box-header">
                    <h2>Sample Development List</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t">
                    @if(Session::has('permission_of_sample_add') || Session::get('user_role') == 'super-admin')
                        <a href="{{url('sample-development/add-sample-development')}}" class="btn btn-sm white m-b add-new-btn btn-sm">
                            <i class="glyphicon glyphicon-plus"></i> Add Sample Development
                        </a>
                    @endif
                    <div class="pull-right">
                        <form action="{{url('sample-development-search')}}" method="GET">
                            <div class="pull-left" style="margin-right: 10px;">
                                <input type="text" class="form-control form-control-sm" name="q" value="{{$q ?? ''}}">
                            </div>
                            <div class="pull-right">
                                <input type="submit" class="btn btn-sm white" value="Search">
                            </div>
                        </form>
                    </div>
                    <br>
                    <br>
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>
                    <hr>
                    <div class="">
                        <table class="table table-bordered" id="">
                            <thead>
                            <tr>
                                <th>Artwork No</th>
                                <th>Artwork Ref.</th>
                                <th>Deadline</th>
                                <th>Shipment Date</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Order</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sample_developments->getCollection()->groupBy('artwork_id') as $group)
                                @foreach($group as $sample_development)
                                    <tr>
                                        <td>{{$sample_development->artwork_no ? $sample_development->artwork_no : '' }}</td>
                                        <td>{{$sample_development->reference ? $sample_development->reference : '' }}</td>
                                        <td>{{date('Y-m-d',strtotime($sample_development->dead_line))}}</td>
                                        <td>{{date('Y-m-d',strtotime($sample_development->shipment_date))}}</td>
                                        <td><span class="{{$sample_development->html_class ? $sample_development->html_class : ''}}">{{$sample_development->status ? $sample_development->status : ''}}</span></td>
                                        <td>
                                            <button class="btn btn-xs btn-default sample-details" sample_id="{{$sample_development->id}}" data-toggle="modal" data-target="#sample-details">Details</button>
                                            <div class="dropdown inline">
                                                <button class="btn btn-xs white dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                                                <div class="dropdown-menu pull-right">
                                                    @if(Session::has('permission_of_sample_quotation') || Session::get('user_role') == 'super-admin')
                                                        @if(!$sample_development->quotationMaster)
                                                            <a class="dropdown-item" href="{{url('price-quotation/create?sample_id='.$sample_development->id.'&artwork_id='.$sample_development->artwork_id)}}">Make Quotation</a>
                                                        @endif
                                                        <a class="dropdown-item">Mail Quotation</a>
                                                        <a class="dropdown-item get-quotation" id="" href="#" artwork_id="{{$sample_development->artwork_id}}" sample_id="{{$sample_development->id}}" data-toggle="modal" data-target="#myModal">Preview Quotation</a>
                                                        <div class="dropdown-divider"></div>
                                                    @endif
                                                    @if(Session::has('permission_of_sample_edit') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                                        <a class="dropdown-item" href="{{url('sample-development/sample-receive/'.$sample_development->id)}}">Receive Sample </a>
                                                        <a class="dropdown-item send-sample" href="#" sample_id="{{$sample_development->id}}" href="#" data-toggle="modal" data-target="#sendSample">Send Sample</a>
                                                        <a class="dropdown-item buyer_approval" sample_id="{{$sample_development->id}}" href="#" data-toggle="modal" data-target="#approval">Buyer Approval</a>
                                                        <a class="dropdown-item" href="{{url('sample-development/re-develop-sample/'.$sample_development->id.'/'.$sample_development->artwork_id)}}">Re-develop Sample</a>
                                                        <div class="dropdown-divider"></div>
                                                    @endif
                                                    @if(Session::has('permission_of_sample_edit') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                                        <a class="dropdown-item" href="{{url('sample-development/' . $sample_development->id . '/edit')}}">Edit</a>
                                                        <a class="dropdown-item" href="{{url('sample-development/' . $sample_development->id . '/delete')}}" onclick="return confirm('Are you sure you want to Delete?');">Delete</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        @if($loop->first)
                                            <td rowspan="{{count($group)}}">
                                                <div class="dropdown inline">
                                                    <button class="btn btn-xs white dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Order</button>
                                                    <div class="dropdown-menu pull-right">
                                                        @if(Session::has('permission_of_master_order_add') || Session::get('user_role') == 'super-admin')
                                                            <a class="dropdown-item" href="{{url('create-master-order?sample_id='.$sample_development->id.'&artwork_id='.$sample_development->artwork_id.'&artwork_ref_id='.$sample_development->artwork_ref_id)}}">Create Order</a>
                                                            <a class="dropdown-item preview_order" href="#" data-toggle="modal" data-target="#preview_order" sample_id="{{$sample_development->id}}">Preview Order</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">{{$sample_developments->render()}}</div>
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
    <div class="modal fade" id="preview_order" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Order Preview</h4>
                </div>
                <div class="modal-body order-details-append-here">
                    <p>Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
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
                    <div class="details-here"></div>
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

            /* Quotation details view popup */

            $('.get-quotation').on('click', function () {
                var sample_id = $(this).attr('sample_id');
                var artwork_id = $(this).attr('artwork_id');
                $('.quotation-append-here').html('<p>Loading...</p>');
                $.ajax({
                    url: '{{url('price-quotation/view-quotation')}}',
                    type: 'GET',
                    data: {_token: '<?php echo csrf_token()?>', sample_id: sample_id, artwork_id: artwork_id},
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
                    url: '{{url('sample-development/sample-send')}}',
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
                    url: '{{url('order/preview-order')}}',
                    type: 'GET',
                    data: {_token: '<?php echo csrf_token()?>', sample_id: sample_id},
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
                    url: '{{url('sample-development/sample-details')}}' + '/' + sample_id,
                    success: function (data) {
                        $('.details-here').html(data);
                    }
                });
            });


        });
    </script>
@endpush
