@extends('skeleton::layout')
@section('title','Yarn Item Ledger')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Item Ledger
                </h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <form id="ledgerForm" class="col-md-12" method="POST">
                        @csrf
                        @include('inventory::yarns.reports.yarn-item-ledger.itemDescriptionModal')
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Item Description</th>
                                <th style="min-width: 100px;">Lot</th>
                                <th>Year</th>
                                <th colspan="2">Date Range</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <select name="factory_id"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                            @foreach($companies as $value)
                                                <option
                                                    @if(auth()->user()->factory_id == $value->id) selected @endif
                                                    value="{{ $value->id }}">
                                                    {{ $value->factory_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input class="form-control form-control-sm search-field text-center"
                                               placeholder="Browse"
                                               id="itemDes"
                                               readonly
                                        >
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select name="yarn_lot[]"
                                                class="form-control form-control-sm search-field text-center c-select select2-input"
                                                multiple="multiple">
                                            @foreach($lots as $value)
                                                <option
                                                    value="{{ $value }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input name="year" type="text"
                                               value="{{ date('Y') }}"
                                               class="form-control form-control-sm search-field text-center"
                                               placeholder="Year">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input name="form_date" type="date"
                                               class="form-control form-control-sm search-field text-center"
                                        >
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input name="to_date" type="date"
                                               class="form-control form-control-sm search-field text-center"
                                        >
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" type="submit">Show</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>

                <div class="body-section" style="margin-top: 0; display:none;" id="reportBody">
                    <div class="row m-t-1">
                        <div class="col-md-12">
                            <div class="pull-right m-b-1">
                                <button class="btn btn-sm" id="yarnReportPdf"><i class="fa fa-file-pdf-o"></i> PDF</button>
                                <button class="btn btn-sm" id="yarnReportExcel">Excel</button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="reportTable"></div>
                            <div id="noData" style="display: none; text-align:center;">
                                <strong>No More Data Available</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="text-align: center;">
                    <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var browseText = '', yarnType = '', yarnComposition = '', yarnCount = '';
        $(document).on('dblclick', '#itemDes', function () {
            $('#itemDesModal').modal('show')
        });

        $(document).on('click', '#saveData', function () {
            if (yarnType) {
                appendBrowseText(yarnType)
            }
            if (yarnComposition) {
                appendBrowseText(yarnComposition)
            }
            if (yarnCount) {
                appendBrowseText(yarnCount)
            }
            $('#itemDes').val(browseText)
            browseText = ''
        })

        function appendBrowseText(type) {
            if (type === 'Select') return;
            if (!browseText) {
                browseText = type
            } else {
                browseText += ', '+type
            }
        }

        $(document).on('change', '#yarnType', function () {
            const data = $('#yarnType').select2('data');
            yarnType = (data[0].text).trim();
        })
        $(document).on('change', '#yarnComposition', function () {
            const data = $('#yarnComposition').select2('data');
            yarnComposition = (data[0].text).trim();
        })
        $(document).on('change', '#yarnCount', function () {
            const data = $('#yarnCount').select2('data');
            yarnCount = (data[0].text).trim();
        })

        $(document).on('click', '#yarnReportPdf', function() {
            const form = document.getElementById('ledgerForm');
            form.setAttribute('action', '/inventory/yarn-item-ledger/report/pdf');
            form.submit();
        });

        $(document).on('click', '#yarnReportExcel', function() {
            const form = document.getElementById('ledgerForm');
            form.setAttribute('action', '/inventory/yarn-item-ledger/report/excel');
            form.submit();
        });

        var page = 1, maxPage = 0;
        $(document).on('submit', '#ledgerForm', function (e) {
            e.preventDefault();
            const reportTable = $("#reportTable");
            const reportBody = $("#reportBody");
            const data = $(this).serializeArray();
            $.ajax({
                method: 'POST',
                url: `/inventory-api/v1/yarn-item-ledger?page=${page}`,
                data: data,
                beforeSend() {
                    $('html,body').css('cursor', 'wait');
                    $("html").css({'background-color': 'black', 'opacity': '0.5'});
                    $(".loader").show();
                },
                complete() {
                    $('html,body').css('cursor', 'default');
                    $("html").css({'background-color': '', 'opacity': ''});
                    $(".loader").hide();
                },
                success(data) {
                    maxPage = data.maxPage
                    reportTable.html(data.view);
                    reportBody.show();
                },
                error(errors) {
                    console.log(errors);
                }
            })
        });

        $(window).on('scroll', function () {
            const reportTable = $("#reportTable");
            if ( page < maxPage )
            {
                if ($(window).height() + $(window).scrollTop() + 50 >= $(document).height()) {
                    const data = $('#ledgerForm').serializeArray();
                    page++;
                    $.ajax({
                        method: 'POST',
                        url: `/inventory-api/v1/yarn-item-ledger?page=${page}`,
                        data: data,
                        beforeSend() {
                            $('html,body').css('cursor', 'wait');
                            $(".loader").show();
                        },
                        complete() {
                            $('html,body').css('cursor', 'default');
                            $(".loader").hide();
                        },
                        success(data) {
                            reportTable.append(data.view)
                        },
                        error(errors) {
                            console.log(errors);
                        }
                    });
                }
            } else {
                $('#noData').show();
            }
        });
    </script>
@endsection
