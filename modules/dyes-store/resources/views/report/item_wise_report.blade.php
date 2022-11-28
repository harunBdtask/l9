@extends('inventory::layout')
@section('content')

    <style>
        h1 {
            text-align: center;
            text-transform: uppercase;
            letter-spacing: -2px;
            font-size: 2.5em;
            margin: 20px 0;
        }

        .container {
            width: 90%;
            margin: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: center;
            padding: 5px 0;
        }

        thead {
            background: #ffffff;
        }

        .fixed {
            top: 56px;
            position: fixed;
            width: auto;
            display: none;
            border: none;
        }

        .scrollMore {
            margin-top: 600px;
        }

        .up {
            cursor: pointer;
        }

        .padding-1 {
            padding: 1%;
        }
    </style>

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Item Wise Stock Summary Report of {{ $store_name ?? '' }} </h2>
                {{-- <span class="pull-right" style="margin-top: -2%;">
                    <a id="pdf" type="button" data-toggle="tooltip" data-placement="top" title="PDF">
                       <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                    </a>|
                    <a id="excel" type="button" data-toggle="tooltip" data-placement="top" title="EXCEL">
                       <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                    </a>
                </span> --}}
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row m-b-2">
                    <form action={{ url("/stores/{$store}/item-wise-summery") }} method="GET" id="form">
                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control form-control-sm"
                                   value="{{ old('first_date') ?? $first_date ?? null }}" name="first_date"
                                   id="first_date" autocomplete="false" placeholder="Select From Date">
                            @component('inventory::alert', ['name' => 'first_date']) @endcomponent
                        </div>
                        <div class="col-md-3 form-group width-custom">
                            <input type="text" class="form-control form-control-sm"
                                   value="{{ old('last_date') ?? $last_date ?? null }}" name="last_date" id="last_date"
                                   autocomplete="false" placeholder="Select To Date">
                            @component('inventory::alert', ['name' => 'last_date']) @endcomponent
                        </div>

                        <div class="col-md-3 form-group">
                            {!! Form::select('item', $all_items, isset($itemId) ? $itemId : null, ['class'=>'form-control', 'id'=>'item', 'placeholder' => 'Select item...']) !!}
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-sm btn-primary" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-12 table-responsive" id="reportTable">
                        <table class="reportTable" id="header-fixed">
                            <thead>
                            <tr>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Particulars</th>
                                <th rowspan="2">Challan No</th>
                                <th colspan="2">Inwards</th>
                                <th colspan="2">Outwards</th>
                                <th colspan="2">Closing Balance</th>
                            </tr>
                            <tr>
                                {{--inward--}}
                                <th>Quantity</th>
                                <th>Value</th>
                                {{--Outward--}}
                                <th>Quantity</th>
                                <th>Value</th>
                                {{--Closing--}}
                                <th>Quantity</th>
                                <th>Value</th>
                            </tr>
                            </thead>
                            @if(isset($item_transaction_date))
                                <tbody>
                                @php
                                    $inwards_grand_value = 0;
                                    $outwards_grand_value = 0;
                                    $closing_grand_value = 0;
                                @endphp
                                @foreach($item_transaction_date as $key => $value)
                                    @php
                                        $stockDetails = $item->itemStock($value);
                                        $inwards_grand_value += $stockDetails['inward_value'];
                                        $outwards_grand_value += $stockDetails['outward_value'];
                                        $closing_grand_value += $stockDetails['closing_value'];
                                    @endphp
                                    @include('inventory::report.item_wise_report_table_row', [
                                        "stockDetails"=> $stockDetails,
                                        "item" => $item
                                    ])
                                @endforeach
                                @include('inventory::report.item_wise_report_table_row_summery',[
                                    "inwards_grand_value"=> $inwards_grand_value,
                                    "outwards_grand_value"=> $outwards_grand_value,
                                    "closing_grand_value"=> $closing_grand_value,
                                ])
                                </tbody>
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="7">Data Not Found</td>
                                </tr>
                                </tbody>
                            @endif
                        </table>
                        {{-- <div class="text-center print-delete"> {{ $categories->appends(request()->except("page"))->links() }}</div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script defer>
        ;(function ($) {
            $.fn.fixMe = function () {
                return this.each(function () {
                    var $this = $(this),
                        $t_fixed;

                    function init() {
                        $t_fixed = $this.clone();
                        $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
                        resizeFixed();
                    }

                    function resizeFixed() {
                        $t_fixed.find("th").each(function (index) {
                            $(this).css("width", $this.find("th").eq(index).outerWidth() + "px");
                        });
                    }

                    function scrollFixed() {
                        var offset = $(this).scrollTop(),
                            tableOffsetTop = $this.offset().top,
                            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
                        if (offset < tableOffsetTop || offset > tableOffsetBottom)
                            $t_fixed.hide();
                        else if (offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
                            $t_fixed.show();
                    }

                    $(window).resize(resizeFixed);
                    $(window).scroll(scrollFixed);
                    init();
                });
            };
        })(jQuery);

        function main() {
            $('#item').select2({
                placeholder: "Select Item",
                allowClear: true
            })

            $('#first_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#last_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#pdf').on('click', function () {
                let pdf = ` <input type="hidden" name="type" id="type" value="pdf"> `;
                let page = ` <input type="hidden" name="page" value="{{request()->query("page") ?? 1}}"> `;
                $("#form").append(page);
                $('#form').append(pdf).submit();
            });

            $('#excel').on('click', function () {
                let excel = ` <input type="hidden" name="type" id="type" value="excel"> `;
                let page = ` <input type="hidden" name="page" value="{{request()->query("page") ?? 1}}"> `;
                $("#form").append(page);
                $('#form').append(excel).submit();
            });
        }

        $(document).ready(function () {
            $("#header-fixed").fixMe();

            main();
        });

    </script>
@endpush