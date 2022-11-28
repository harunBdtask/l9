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

        tbody tr:nth-child(even) {
            background: #ECF0F1;
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
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Daily Report of {{ $store_name }} </h2>
                <span class="pull-right" style="margin-top: -2%;">
                    <a id="pdf" type="button" data-toggle="tooltip" data-placement="top" title="PDF">
                       <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                    </a>|
                    <a id="excel" type="button" data-toggle="tooltip" data-placement="top" title="EXCEL">
                       <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                    </a>
                </span>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row m-b-2">
                    <form action={{ url("/stores/daily/{$store}/report") }} method="GET" id="form">
                        {{-- @csrf --}}
                        {{--first date--}}
                        <div class="col-md-3 form-group">
                            <input type="text" class="form-control form-control-sm"
                                   value="{{ old('first_date') ?? $first_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                                   name="first_date"
                                   id="first_date" autocomplete="false" placeholder="Select From Date">
                            @component('inventory::alert', ['name' => 'first_date']) @endcomponent
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
                        <p style="font-size: 12px;">Reporting Date : {{ $reporting_date }}</p>
                        <table class="reportTable" id="header-fixed">
                            <thead>
                            <tr>
                                <th rowspan="2">Particulars</th>
                                <th colspan="3">Inwards</th>
                                <th colspan="3">Outwards</th>
                            </tr>
                            <tr>

                                {{--inward--}}
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Value</th>
                                {{--Outward--}}
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Value</th>
                            </tr>
                            </thead>

                            @if(isset($items))
                                <tbody>
                                    @php
                                        $inwards_grand_value = 0;
                                        $outwards_grand_value = 0;
                                    @endphp
                                    @foreach($items as $key => $item)
                                    @php
                                        $stockDetails = $item->stock($first_date,$last_date);
                                        $inwards_grand_value += $stockDetails['inward_value'];
                                        $outwards_grand_value += $stockDetails['outward_value'];
                                    @endphp
                                        @include('inventory::report.daily_report_table_row',[
                                            "stockDetails"=>$stockDetails
                                        ])
                                    @endforeach
                                    @include('inventory::report.daily_report_table_row_summery',[
                                        "inwards_grand_value"=>$inwards_grand_value,
                                        "outwards_grand_value"=>$outwards_grand_value,
                                    ])
                                </tbody>
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="13">Data Not Found</td>
                                </tr>
                                </tbody>
                            @endif
                        </table>
                        <div class="text-center print-delete"> {{ $items->appends(request()->except("page"))->links() }}</div>
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

            $('#first_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            // $('#first_date').change(function (e) {
            //     e.preventDefault();
            //     var value = $(this).val();
            //     var test = window.location.href + '?date=' + value;
            //     window.history.pushState(null, document.title, test);
            // });

            // $('#last_date').datepicker({
            //     format: 'yyyy-mm-dd',
            //     autoclose: true
            // });

            $('#pdf').on('click', function () {
                let pdf = ` <input type="hidden" name="type" value="pdf"> `;
                let page = ` <input type="hidden" name="page" value="{{request()->query("page") ?? 1}}"> `;
                $("#form").append(page);
                $('#form').append(pdf).submit();
            });

            $('#excel').on('click', function () {
                let excel = ` <input type="hidden" name="type" value="excel"> `;
                let page = ` <input type="hidden" name="page" value="{{request()->query("page") ?? 1}}"> `;
                $("#form").append(page);
                $('#form').append(excel).submit();
            });
        }

        $(document).ready(function () {
            $("#header-fixed").fixMe();

            main();
        });

        // $(document).ready(main);
    </script>
@endpush
