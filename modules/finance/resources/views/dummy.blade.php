@extends('finance::layout')
@push('style')
    <style>
        table thead tr th {
            white-space: nowrap;
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

        .in-print {
            display: none !important;
        }

        .reportTable th,
        .reportTable td {
            border: 1px solid #b7b7b7;
        }

        .reportTable > tr > td > input {
            border: none;
            padding: 5px;
        }

        .reportTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
            font-size: 16px !important;
            border-collapse: collapse;
        }

        .width-100-percent {
            width: 100% !important;
            border: none;
            padding: .33em 1em !important;
        }

        .txt-hor {
            transform: rotate(270deg);
            height: 150px !important;
        }

    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box knit-card">
            <div class="box-header">

                <div class="row print-delete">
                    <div class="col-md-6">
                        <h2>Time And Action Calender</h2>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="box-body b-t" style="margin-top: -10px">

                <table class="reportTable" cellpadding="0" ; cellspacing="0">
                    <tr style="background: ghostwhite;">
                        <th width="50px"></th>
                        <th>Key Process</th>
                        <th class="txt-hor">Planning Start</th>
                        <th class="txt-hor">Planning End</th>
                        <th class="txt-hor">Duration <br>(days)</th>
                        <th class="txt-hor">Actual Start</th>
                        <th class="txt-hor">Actual End</th>
                        <th class="txt-hor">Duration <br>(days)</th>
                        <th>Responsibility</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach(range(1, 22) as $num)
                        <tr>
                            <td>{{ $num < 10 ? '0' . $num : $num }}</td>
                            <td><input type="text" class="width-100-percent" /></td>
                            <td><input type="text" class="width-100-percent date-input" /></td>
                            <td><input type="text" class="width-100-percent date-input" /></td>
                            <td><input type="text" class="width-100-percent" /></td>
                            <td><input type="text" class="width-100-percent date-input" /></td>
                            <td><input type="text" class="width-100-percent date-input" /></td>
                            <td><input type="text" class="width-100-percent" /></td>
                            <td><input type="text" class="width-100-percent" /></td>
                            <td><input type="text" class="width-100-percent" /></td>
                        </tr>
                    @endforeach

                </table>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(document).ready(function () {
            $(".date-input").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                title: 'Planning Start'
            });
        })
    </script>
@endpush