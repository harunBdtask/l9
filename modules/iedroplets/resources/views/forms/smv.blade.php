@extends('iedroplets::layout')
@section('title', 'SMV View & Update')
@section('content')
    <style>
        .custom-blue-div {
            background-color: #4daeef !important;
            padding: 6px;
            color: white !important;
            border-radius: 4px;
            margin: 2px;
        }

        .select2-container .select2-selection--single {
            background-color: white !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: black !important;
        }

        .btn-success {
            background-color: #4daeef !important;
        }

        .success {
            color: black !important;
            background-color: #97d5ff !important;
        }
    </style>
    <div class="padding">
        @if(Session::has('permission_of_smv_justification_add') || Session::has('permission_of_show_smv_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header text-center">
                            <h2>SMV Justification</h2>
                        </div>
                        <div class="box-divider m-a-0"></div>
                        <div class="box-body">
                            <div class="js-response-message text-center"></div>
                            {!! Form::open(["id"=>"smv_search", "url"=>"/get-smv-orders"]) !!}
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-2 custom-blue-div">
                                        <label><b>Year</b></label>
                                        {!! Form::selectRange('year',
                                            \Carbon\Carbon::now()->subYears(5)->format('Y'), \Carbon\Carbon::now()->addYears(5)->format('Y'), date('Y'),
                                            ['class' => 'year-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Year']) !!}
                                    </div>
                                    <div class="col-sm-2 custom-blue-div">
                                        <label><b>Month</b></label>
                                        {!! Form::selectMonth('month', date('m'), ['class' => 'month-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Month']) !!}
                                    </div>
                                    <div class="col-sm-2 custom-blue-div">
                                        <label><b>Buyer</b></label>
                                        {!! Form::select('buyer_id', $buyers, null, ['class' => 'smv-buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                                    </div>
                                    <div class="col-sm-2 custom-blue-div">
                                        <label><b>Style</b></label>
                                        {!! Form::select('order_id',[], null, ['class' => 'smv-style-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Style']) !!}
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info" type="submit"
                                                style="margin-top: 37px !important;"><i
                                                class="fa fa-search"></i>Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                            <table class="reportTable">
                                <thead>
                                <tr style="background-color: mintcream">
                                    <th>Buyer</th>
                                    <th>Style</th>
                                    <th>Item</th>
                                    <th>Order SMV</th>
                                    <th style="width:10%">Factory SMV</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="smv-update-order-list">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('script-head')
    <script type="text/javascript">
        let month;
        $(document).on('change', '.smv-buyer-select', function () {
            $('.smv-style-select').empty().change();
            $('.smv-update-order-list').empty();
            let buyer_id = $(this).val();
            if (buyer_id) {
                $.ajax({
                    type: 'GET',
                    url: '/common-api/buyers-styles/' + buyer_id,
                    success: function (response) {
                        let styleDropdown = '<option value="">Select a Style</option>';
                        if (Object.keys(response).length > 0) {
                            $.each(response, function (index, data) {
                                styleDropdown += '<option value="' + data.id + '">' + data.text + '</option>';
                            });
                            $('.smv-style-select').html(styleDropdown);
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#smv_search', function (e) {
            e.preventDefault();
            $('.smv-update-order-list').empty();
            month = $('.month-select').val();
            let formData = $(this).serializeArray();
            if (month) {
                $.ajax({
                    type: 'post',
                    url: '/get-smv-orders',
                    data: formData,
                    success: function (response) {
                        if (response) {
                            let tr = '';
                            $.each(response, function (index, data) {

                                tr += `<tr class="odd gradeX tr-height">
                                    <td>${data.buyer}</td>
                                    <td>${data.style_name}</td>
                                    <td>${data.item_name}</td>
                                    <td style="text-align: right">${data.order_smv}</td>
                                    <td>
                                        <input type="number" name="factory_smv" style="text-align: right"
                                            class="form-control form-control-sm factory_smv"
                                            value="${data.factory_smv}">
                                    </td>
                                    <td>
                                        <input type="text" name="remarks" class="form-control form-control-sm remarks"
                                            value="${data.remarks ? data.remarks : ''}">
                                    </td>
                                    <td>
                                        <button type="button" value="${data.order_id}" data-item="${data.item_id}"
                                           class="btn btn-xs btn-success smv-update-btn">Update</button>
                                    </td>
                                </tr>`;

                            });
                            $('.smv-update-order-list').html(tr);
                        }
                    }
                });
            }
        });

        $(document).on('click', '.smv-update-btn', function () {
            let $closesTr = $(this).closest('tr');

            $closesTr.addClass('success');
            $closesTr.find(".smv-update-btn").attr("disabled", true);

            let factory_smv = $closesTr.find('.factory_smv').val();
            let remarks = $closesTr.find('.remarks').val();
            let orderId = $(this).val();
            let itemId = $(this).data('item');
            let token = $('meta[name="csrf-token"]').attr('content');

            if (factory_smv && orderId) {
                $.ajax({
                    type: 'post',
                    url: '/update-order-smv/' + orderId,
                    data: {factory_smv: factory_smv, remarks: remarks, itemId: itemId, _token: token},
                    success: function (response) {
                        if (response.code === 200) {
                            $('.js-response-message').html(`<div class="alert alert-success">${response.message}</div>`)
                                .fadeIn().delay(2000).fadeOut(2000);
                            $closesTr.find(".smv-update-btn").removeAttr("disabled");
                        } else {
                            $('.js-response-message').html(`<div class="alert alert-error">Something went wrong</div>`)
                                .fadeIn().delay(2000).fadeOut(2000);
                        }
                    }
                });
            }
        });
    </script>
@endpush
