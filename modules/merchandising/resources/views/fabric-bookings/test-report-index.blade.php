@extends('skeleton::layout')
@section('title','Test Report')
@push('style')
    <style>
        .select-option {
            min-height: 2.375rem !important;
        }

        .custom-input {
            width: 200px;
        }

        table thead tr th {
            white-space: nowrap;
        }

        .select2-selection--single {
            border-radius: 0px !important;
            border: 1px solid #e7e7e7 !important;
        }
    </style>
@endpush
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <div class="col-md-6">
                    <h2>Print View Permission</h2>
                </div>
                {{--                <div class="col-md-6" align="right">--}}
                {{--                    @if(Request::has('buyer_id'))--}}
                {{--                        <ul>--}}
                {{--                            <li style="list-style: none;display: inline-block"><a class="" href="{{url('proft-loss-statement-report-pdf?buyer_id='.request()->buyer_id.'&order_id='.request()->order_id.'&budget_id='.request()->budget_id)}}" class="hidden-print btn btn-xs" title="Download this pdf"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a></li>--}}
                {{--                        </ul>--}}
                {{--                    @endif--}}
                {{--                </div>--}}
            </div>
            <div class="clearfix"></div>
            <div class="box-body b-t ">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                <div class="">
                    <form action="{{ url('fabric-bookings/test-report-value') }}" method="post">
                        @csrf
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <td>Company</td>
                                <td>Buyer</td>
                                <td>Select Page</td>
                                <td>Select Print</td>
                                <td>Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 200px">
                                    <select name="company_id" style="height: 40px; width: 200px;"
                                            class="form-control form-control-sm select2-input">

                                        @foreach($companies as $key => $company)
                                            <option
                                                value="{{ $key }}" {{  request()->company_id ? 'selected' : null }}>{{ $company }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="max-width: 350px">

                                    <select name="buyer_id[]" style="height: 40px;" class="form-control form-control-sm select2-input"
                                            multiple id="select_buyer_id"
                                            data-select="true"
                                    >
                                        <option
                                            value="All" {{ in_array('All', request()->buyer_id ?? []) ? 'selected' : null }}>
                                            Select All

                                        </option>
                                        @foreach($buyers as $key => $buyer)
                                            @if($key == 0)
                                                <option
                                                    value="All" {{ in_array('All', request()->buyer_id ?? []) ? 'selected' : null }}>
                                                    Select All
                                                </option>
                                            @endif
                                            <option
                                                value="{{ $key }}" {{ in_array($buyer, $buyer_id ?? []) ? 'selected' : null }}>{{ $buyer }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{--                                <td>{!! Form::select('order_id',[],request()->order_id ?? null,['class'=>'custom-input form-control form-control-sm order_id','id'=>'order_id','placeholder'=>'Select Booking No',isset($purchase_order_data)? 'style="pointer-events: none;"' : '']) !!}</td>--}}
                                </td>
                                <td style="width: 200px">
                                    <select name="page_id" style="height: 40px; width: 200px;"
                                            class="form-control form-control-sm select2-input" id="page_id">
                                        @foreach(($pages) as $key => $page)
                                            <option
                                                value="{{ $key }}" {{  request()->page_id ? 'selected' : null }}>{{ $page }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 200px">
                                    <select name="view_id" style="height: 40px; width: 200px;"
                                            class="form-control form-control-sm select2-input" id="view_id">

                                        @foreach($views as $key => $view)
                                            <option
                                                value="{{ $key }}" {{  request()->view_id ? 'selected' : null }}>{{ $view }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 100px">
                                    <button class="btn btn-xs btn-success">save</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(function () {
            /* repopulate form input while report generate mode */
            var buyer_id = '{{request()->buyer_id}}';
            var order_id = '{{request()->order_id}}';
            var page_id = '{{ request()->page_id }}'


            $("#select_buyer_id").on("change", function () {

                if (($(this).find(":selected").val() === "All")) {
                    if ($(this).attr("data-select") === "false")
                        $(this).attr("data-select", "true").find("option").prop("selected", true);
                    else
                        $(this).attr("data-select", "false").find("option").prop("selected", false);
                }
            });
        $( "#page_id" ).on("change", function () {
            var val = $(this).val();
            $.ajax({
                url: '{{url('fabric-bookings/test-report-value')}}' + '/' + val,
                type: 'GET',
                context: this,
                success: function (data) {
                    var select = $('form select[name= view_id]');
                    select.empty();

                    var options = '<option>Select View</option>';
                    $.each(data, function (index, value) {
                        options += '<option value="' + index + '">' + value + '</option>';
                    });
                    $('#view_id').html(options);
                }
            });


        });

            {{--if (buyer_id) {--}}
            {{--        get_order();--}}
            {{--    }--}}
            {{--    if (order_id) {--}}
            {{--        get_budget();--}}
            {{--    }--}}
            {{--    $('select').select2();--}}
            {{--    $('.buyer_id').on('change', function () {--}}
            {{--        var buyer_id = $(this).val();--}}
            {{--        $('.buyer_id_hidden').val(buyer_id);--}}
            {{--        $.ajax({--}}
            {{--            url: '{{url('get-orders-by-buyer-budget')}}' + '/' + buyer_id,--}}
            {{--            type: 'GET',--}}
            {{--            context: this,--}}
            {{--            success: function (data) {--}}
            {{--                var options = '<option>Select Style / Order</option>';--}}
            {{--                $.each(data, function (index, value) {--}}
            {{--                    options += '<option value="' + index + '">' + value + '</option>';--}}
            {{--                });--}}
            {{--                $('.order_id').html(options);--}}
            {{--            }--}}
            {{--        });--}}
            {{--    });--}}

            {{--    $('.order_id').on('change', function () {--}}
            {{--        var order_id = $(this).val();--}}
            {{--        $('.order_id_hidden').val(order_id);--}}
            {{--        $.ajax({--}}
            {{--            url: '{{url('get-budget-with-order')}}' + '/' + order_id,--}}
            {{--            type: 'GET',--}}
            {{--            context: this,--}}
            {{--            success: function (data) {--}}
            {{--                var options = '<option value="">Select PO</option>';--}}
            {{--                $.each(data, function (index, value) {--}}
            {{--                    options += '<option value="' + index + '">' + value + '</option>';--}}
            {{--                });--}}
            {{--                $('.budget_id').html(options);--}}
            {{--                set_hidden_master_field_value();--}}
            {{--            }--}}
            {{--        });--}}
            {{--    });--}}

            {{--    $('.purchase_order_id').on('change', function () {--}}
            {{--        var purchase_order_id = $(this).val();--}}
            {{--        $('.purchase_order_id_hidden').val(purchase_order_id);--}}
            {{--        var budget_status = '{{request()->segment(2)}}';--}}
            {{--        if (budget_status == 'create') {--}}
            {{--            $.ajax({--}}
            {{--                url: '{{url('check-if-budget-exists')}}' + '/' + purchase_order_id,--}}
            {{--                type: 'GET',--}}
            {{--                success: function (data) {--}}
            {{--                    if (data != 00) {--}}
            {{--                        alert('Budget Already Created Under This Purchase Order');--}}
            {{--                        window.location.href = "{{url('budget/update?purchase_order_id=')}}" + data;--}}
            {{--                    }--}}
            {{--                }--}}
            {{--            });--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            {{--function get_order() {--}}
            {{--    var buyer_id = $('.buyer_id').val();--}}
            {{--    var order_id = '{{request()->order_id}}';--}}
            {{--    $.ajax({--}}
            {{--        url: '{{url('get-orders-by-buyer-budget')}}' + '/' + buyer_id,--}}
            {{--        type: 'GET',--}}
            {{--        context: this,--}}
            {{--        success: function (data) {--}}
            {{--            var options = '<option>Select Style / Order</option>';--}}
            {{--            $.each(data, function (index, value) {--}}
            {{--                var selected = index == order_id ? 'selected="selected"' : '';--}}
            {{--                options += '<option value="' + index + '" ' + selected + ' >' + value + '</option>';--}}
            {{--            });--}}
            {{--            $('.order_id').html(options).select2();--}}
            {{--        }--}}
            {{--    });--}}
            {{--}--}}

            {{--function get_budget() {--}}
            {{--    var order_id = '{{request()->order_id}}';--}}
            {{--    var budget_id = '{{request()->budget_id}}';--}}
            {{--    $('.order_id_hidden').val(order_id);--}}
            {{--    $.ajax({--}}
            {{--        url: '{{url('get-budget-with-order')}}' + '/' + order_id,--}}
            {{--        type: 'GET',--}}
            {{--        context: this,--}}
            {{--        success: function (data) {--}}
            {{--            var options = '<option value="">Select PO</option>';--}}
            {{--            $.each(data, function (index, value) {--}}
            {{--                var selected = index == budget_id ? 'selected="selected"' : '';--}}
            {{--                options += '<option value="' + index + '" ' + selected + '>' + value + '</option>';--}}
            {{--            });--}}
            {{--            $('.budget_id').html(options).select2();--}}
            {{--        }--}}
            {{--    });--}}
        });
    </script>
@endpush
