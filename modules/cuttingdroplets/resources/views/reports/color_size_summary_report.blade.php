@extends('cuttingdroplets::layout')
@section('title', 'Color Size Summary Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Color Size Summary Report
                            <span class="pull-right">
{{--                <a download-type="pdf" class="color-size-summary-report-download-btn">--}}
                                {{--                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>--}}
                                {{--                </a>--}}
                                {{--                |--}}
                <a download-type="xls" class="color-size-summary-report-download-btn">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body cutting-no">
                        @include('partials.response-message')
                        <form id="color-size-summary">
                            <div class="form-group color-size-summary-report">
                                <div class="row m-b">
                                    <div class="col-sm-2">
                                        <label>Year</label>
                                        {!! Form::selectRange('year', 2021, \Carbon\Carbon::now()->addYears(10)->format('Y'), \Carbon\Carbon::now()->format('Y'), ['class' => 'year form-control select2-input', 'placeholder' => 'Select Year']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Week</label>
                                        {!! Form::select('week', $weeks, \Carbon\Carbon::now()->weekOfYear,['class' => 'week form-control select2-input', 'placeholder' => 'Select Week']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Buyer</label>
                                        {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select-cutting-no form-control select2-input', 'placeholder' => 'Select a Buyer', 'id'=>'buyer_id']) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="order_id">Order/Style</label>
                                        <select class="buyer-select-cutting-no form-control select2-input"
                                                name="order_id" id="order_id">
                                            <option selected disabled hidden>--Select Style--</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Cut Off</label>
                                        {!! Form::select('cut_off', $cutOffs, null, ['class' => 'cut-off-select-cutting-no form-control select2-input', 'placeholder' => 'Select Cut off', 'id'=>'cut_off', 'disabled' => true]) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-sm btn-primary" style="margin-top: 25px"><i
                                                class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="loader" style="display: none; text-align: center;">
                            <img src="{{asset('loader.gif')}}" style="height: 40px;" alt="loader">
                        </div>
                        <div id="parentTableFixed" class="table-responsive color-size-summary-report-table">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Color Size Summary Report
        $(document).on("submit", "#color-size-summary", function (e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            if (!formData[1].value) {
                alert("Please select week!");
                return false;
            }
            if (!formData[2].value) {
                alert("Please select buyer!");
                return false;
            }


            $.ajax({
                url: "/color-size-summary-report/get-report",
                type: "POST",
                dataType: "html",
                data: formData,
                beforeSend() {
                    $('html,body').css('cursor', 'wait');
                    $("html").css({'background-color': 'black', 'opacity': '0.5'});
                    $(".loader").show();
                },
                complete() {
                    $('html,body').css('cursor', 'default');
                    $("html").css({'background-color': '', 'opacity': ''});
                    $('.loader').hide();
                },
                success(response) {
                    let queryString = '';
                    queryString = `year=${formData[0].value}&week=${formData[1].value}`;
                    queryString += formData[2] ? `&buyer_id=${formData[2].value}` : '';
                    queryString += formData[3] ? `&cut_off=${formData[3].value}` : '';
                    queryString += formData[4] ? `&order_id=${formData[4].value}` : '';

                    $(".color-size-summary-report-download-btn").attr('href',
                        `/color-size-summary-report/get-report-xls?${queryString}`
                    )
                    $(".color-size-summary-report-table").html(response);
                }
            })
        });

        $(document).on('change', '#buyer_id', function () {
            const buyerId = $(this).val()
            const cutOff = $("#cut_off");
            if(buyerId) {
                cutOff.prop('disabled', false);
            } else {
                cutOff.prop('disabled', true);
                cutOff.val('').trigger("change");
            }
            $.ajax({
                url: "/common-api/buyers-styles/"+buyerId,
                type: "GET",
                success(response) {
                    $.each(response, function (index, item) {
                        $('#order_id').append(`<option value="${response[index].id}">
                               ${response[index].text}
                        </option>`);
                    })
                }
            })
        })
    </script>
@endsection
