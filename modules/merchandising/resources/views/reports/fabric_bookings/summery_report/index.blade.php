@extends('skeleton::layout')
@section("title") Fabric Booking Summery Reports @endsection
@push('style')
    <style>
        .grand-row {
            font-size: 16px !important;
            font-weight: bold;
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header btn-info">
                <h2>Fabric Booking Details Reports
                    <span class="pull-right">
                        <i data-url="/fabric-booking-summery-report/pdf"
                           style="color: #DC0A0B; cursor: pointer"
                           class="text-danger fa fa-file-pdf-o downloadBtn"></i>
                    </span>
                </h2>
                <div class="clearfix"></div>
            </div>

            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="{{ url('/fabric-booking-summery-report') }}" method="post" id="searchForm" >
                        <div class="col-sm-12 ">
                            <table class="reportTable">
                                <tr>
                                    <th style="width: 200px;">Buyer</th>
                                    <th style="width: 200px;">Merchandiser</th>
                                    <th style="width: 200px;" >Fabric Booking Id</th>
                                    <th style="width: 200px;">Budget Uq Id</th>
                                    <th style="width: 200px;">Style Name</th>
                                    <th style="width: 200px;">Type</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control select2-input" name="buyer_id" id="buyer_id">
                                            <option value="">Select</option>
                                            @foreach ($buyers as  $key => $buyer)
                                                <option value="{{ $key }}">{{ $buyer}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><select class="form-control select2-input" name="merchandiser_id" id="merchandiser_id">
                                            <option value="">Select</option>
                                            @foreach ($merchandisers as  $key => $merchandiser)
                                                <option value="{{ $merchandiser }}">{{ $merchandiser}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2-input" name="booking_id" id="booking_id">
                                            <option value="">Select</option>
                                            @foreach ($booking_ids as  $key => $booking_id)
                                                <option value="{{ $booking_id }}">{{ $booking_id}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2-input" name="detail_id" id="detail_id">
                                            <option value="">Select</option>
                                            @foreach ($details_ids as  $key => $detail_id)
                                                <option value="{{ $detail_id }}">{{ $detail_id}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2-input" name="style_name" id="style_name">
                                            <option value="">Select</option>
                                            @foreach ($styles as  $key => $style)
                                                <option value="{{ $style }}">{{ $style}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2-input" name="type" id="type">
                                            <option value="">Select</option>
                                            @foreach ($types as  $key => $type)
                                                <option value="{{ $key }}">{{ $type}}</option>
                                            @endforeach
                                        </select>
                                    </td>


                                    <td>
                                        <input name="form_date" id="form_date"
                                               style="height: 32px;" type="text"
                                               class="form-control form-control-sm datepicker"
                                               autocomplete="off">
                                    </td>

                                    <td>
                                        <input name="to_date" id="to_date"
                                               style="height: 32px;" type="text"
                                               class="form-control form-control-sm datepicker"
                                               autocomplete="off">
                                    </td>


                                    <td>
                                        <button type="submit" id="ColorWiseOrderVolumeReport"
                                                class="btn btn-sm btn-info"
                                                name="type" title="Details">
                                            <em class="fa fa-search"></em>
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </form>
                </div>
                <br>


                <div id="parentTableFixed" class="table-responsive report-div"
                     style="max-height: fit-content!important;">
                </div>

            </div>
            <!-- <div class="box-body">
                <form action="{{ url('/fabric-booking-summery-report') }}" method="post" id="searchForm">
                    <div class="row m-b">
                        <div class="col-sm-3">
                            <label>Fabric Booking Id</label>
                            {!! Form::text('unique_id', request('unique_id'), [
                                'class' => 'buyer-select form-control form-control-sm',
                                'placeholder' => 'Write a Booking Id'
                            ]) !!}
                        </div>
                        <div class="col-sm-3">
                            <label>Budget UQ Id</label>
                            {!! Form::text('budget_unique_id', request('budget_unique_id'), [
                                'class' => 'buyer-select form-control form-control-sm',
                                'placeholder' => 'Write a Budget'
                            ]) !!}
                        </div>
                        <div class="col-sm-3">
                            <label>Style</label>
                            {!! Form::select('style_name', $styles ?? [], request('style_name'), [
                                'class' => 'buyer-select form-control select2-input',
                                'placeholder' => 'Select a Style'
                            ]) !!}
                        </div>
                        <div class="col-sm-3" style="margin-top:29px;">
                            <button type="submit" class="btn btn-sm btn-info form-control-sm">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div id="parentTableFixed" class="table-responsive report-div"
                     style="max-height: fit-content!important;">
                </div>

            </div> -->
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on('submit', '#searchForm', function (e) {
            e.preventDefault();
            let form = $(this).serializeArray();
            let url = "/fabric-booking-summery-report/get-report-data";

            $.ajax({
                url: url,
                type: "get",
                data: form,
                dataType: "html",
                success(response) {
                    $(".report-div").html(response);
                }
            })
        });

        $(document).on('click', '.downloadBtn', function () {
            let form = $('#searchForm').serialize();
            let url = $(this).data('url');
            url += `?${form}`;
            location.assign(url);
        });
    </script>
@endsection
