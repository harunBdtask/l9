@extends('misdroplets::layout')
@section('title', 'Cut To Finish Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Cut To Finish Report</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>

                    <div class="box-body">
                        @include('partials.response-message')
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-2">
                                    <label for="start_date">Date</label>
                                    <input type="date"
                                           name="start_date"
                                           id="start_date"
                                           class="form-control form-control-sm"
                                    />
                                </div>

                                <div class="col-sm-2">
                                    <label for="end_date">End Date</label>
                                    <input type="date"
                                           name="end_date"
                                           id="end_date"
                                           class="form-control form-control-sm"
                                    />
                                </div>

                                <div class="col-sm-2">
                                    <label for="buyer_id">Buyer</label>
                                    <select name="buyer_id"
                                            id="buyer_id"
                                            class="buyer-select form-control form-control-sm select2-input"
                                    >
                                        <option value="" selected>SELECT</option>
                                        @foreach($buyers as $buyer)
                                            <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('buyer_id'))
                                        <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                    @endif
                                </div>
                                <div class="col-sm-2">
                                    <label for="order_id">Style/Order</label>
                                    <select name="order_id"
                                            id="order_id"
                                            class="style-select form-control form-control-sm select2-input"
                                    >
                                        <option value="" selected>SELECT</option>
                                    </select>
                                    @if($errors->has('order_id'))
                                        <span class="text-danger">{{ $errors->first('order_id') }}</span>
                                    @endif
                                </div>

                                <div class="col-sm-2" style="padding-top: 30px">
                                    <button type="button"
                                            id="search_btn"
                                            class="btn btn-info btn-sm"
                                    >
                                        Search
                                    </button>
                                </div>

                                <div class="col-sm-2" style="text-align:right; padding-top: 30px">
                                    <a class="hidden-print btn btn-xs download-btn"
                                       href="/cut-to-finish-report/generate/pdf"
                                       title="Print this document">
                                        <i class="fa fa-file-pdf-o text-danger"></i>&nbsp;PDF
                                    </a>
                                    <a class="hidden-print btn btn-xs download-btn"
                                       href="/cut-to-finish-report/generate/xls"
                                       title="Print this document">
                                        <i class="fa fa-file-excel-o text-success"></i>&nbsp;Excel
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div id="parentTableFixed" class="table-responsive report-div" style="overflow: auto;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script src="{{ asset('js/tableHeadFixer.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#fixTable").tableHeadFixer();
        });

        const buyerSelectDom = $('[name="buyer_id"]');
        const orderSelectDom = $('[name="order_id"]');
        buyerSelectDom.change(() => {
            orderSelectDom.empty().val('').select2();
            $.ajax({
                url: "/utility/get-styles-for-select2-search",
                type: "get",
                data: {'buyer_id': buyerSelectDom.val()},
                success({results}) {
                    orderSelectDom.empty();
                    orderSelectDom.html(`<option value="" selected>SELECT</option>`);
                    results.forEach(el => {
                        let html = `<option value="${el.id}">${el.text}</option>`;
                        orderSelectDom.append(html);
                    });
                }
            })
        });

        $(document).on('click', '#search_btn', function () {
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();
            let buyer_id = $("#buyer_id").val() ?? '';
            let order_id = $("#order_id").val() ?? '';
            let queryString = new URLSearchParams({start_date, end_date, buyer_id, order_id});

            $.ajax({
                url: `/cut-to-finish-report/generate?${queryString}`,
                type: "post",
                dataType: "html",
                success(res) {
                    $(".report-div").html(res);
                }
            })
        });

        $(document).on('click', '.download-btn', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();
            let buyer_id = $("#buyer_id").val() ?? '';
            let order_id = $("#order_id").val() ?? '';
            let queryString = '?' + (new URLSearchParams({start_date, end_date, buyer_id, order_id}));
            url += queryString;
            location.assign(url);
        });
    </script>
@endpush
