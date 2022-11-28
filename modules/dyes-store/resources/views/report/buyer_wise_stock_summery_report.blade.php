@extends('inventory::layout')
@section('content')
    <style>


        .table-responsive {
            /**make table can scroll**/
            max-height: 400px;
            overflow: auto;
            /** add some style**/
        }



        th {
            padding: 0 !important;
        }

        th,
        tr,
        td {
            border: 1px solid #696969 !important;
            text-align: center;
            font-size: 12px;
        }
        .tr-header-background{
            background: darkseagreen;
        }
        .tr-table-data-background{
            background: powderblue;
        }
        .tr-subTotal{
            background: burlywood;
        }
        .tr-grandTotal{
            background: darkseagreen;
            height: 30px;
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Buyer Wise Stock Summary Report</h5>
                                 <span class="pull-right" style="margin-top: -2%;">
                                    <a id="pdf" type="button" data-toggle="tooltip" data-placement="top" title="PDF">
                                       <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                                    </a>|
                                    <a id="excel" type="button" data-toggle="tooltip" data-placement="top" title="EXCEL">
                                       <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                                    </a>
                                 </span>
                            </div>
                        </div>
                    </div>
                    <div class="box-body b-t">
                        <div class="row m-b-2">
                            <form action={{ url('/buyer-wise-stock-summary-report') }} method="GET" id="form">
                                {{-- first date --}}
                                <div class="col-md-3 form-group">
                                    <input type="text" class="form-control form-control-sm" value="{{ old('first_date') ?? ($first_date ?? null) }}"
                                        name="first_date" id="first_date" autocomplete="false" placeholder="Select From Date">
                                    @component('inventory::alert', ['name' => 'first_date']) @endcomponent
                                </div>
                                {{-- last date --}}
                                <div class="col-md-3 form-group">
                                    <input type="text" class="form-control form-control-sm" value="{{ old('last_date') ?? ($last_date ?? null) }}"
                                        name="last_date" id="last_date" autocomplete="false" placeholder="Select To Date">
                                    @component('inventory::alert', ['name' => 'last_date']) @endcomponent
                                </div>
                                <div class="col-md-3 form-group">
                                    <input type="text" class="form-control form-control-sm" value="{{ old('buyer') ?? ($buyer ?? null) }}" name="buyer"
                                        id="buyer" autocomplete="false" placeholder="Search by buyer">
                                </div>
                                <div class="col-md-3 form-group">
                                    <button class="col-md-12 form-control form-control-sm  btn-primary" type="submit">
                                        Submit
                                    </button>
                                </div>
{{--                                <div class="col-md-3">--}}
{{--                                    <div class="form-group search-btn">--}}
{{--                                        <label for="" class="col-md-12 m-t-1"></label>--}}
{{--                                        {!! Form::submit('submit', ['class' => 'btn btn-sm btn-primary form-control', 'id' => 'submit']) !!}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped reportTable" id="tableFixed">
                                @includeIf('inventory::report.includes.buyer_wise_stock_summery_report_table', [
                                'first_date' => $first_date,
                                'last_date' => $last_date,
                                ])
                                {{-- <tfoot></tfoot> --}}
                            </table>
                        </div>
                        <div class="text-center print-delete"> {{ $buyers->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(document).ready(function() {
            // setSelect2();
            main();
        });

        function setSelect2() {
            $('select').select2();
        }

        function main() {

            $('#first_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#last_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#pdf').on('click', function() {
                let pdf = ` <input type="hidden" name="type" value="pdf"> `;
                let page = ` <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}"> `;
                $("#form").append(page);
                $('#form').append(pdf).submit();
            });

            $('#excel').on('click', function() {
                let excel = ` <input type="hidden" name="type" value="excel"> `;
                let page = ` <input type="hidden" name="page" value="{{ request()->query('page') ?? 1 }}"> `;
                $("#form").append(page);
                $('#form').append(excel).submit();
            });
        }







        // //to fixed top of table header
        'use strict'
        window.onload = function(){
            var tableCont = document.querySelector('.table-responsive')
            /**
             * scroll handle
             * @param {event} e -- scroll event
             */
            function scrollHandle (e){
                // var scrollTop = this.scrollTop;
                // this.querySelector('thead').style.transform = 'translateY(' + scrollTop + 'px)';

                var translate = "translate(0," + this.scrollTop + "px)";
                this.querySelector("thead").style.transform = translate;

            }

            tableCont.addEventListener('scroll', scrollHandle)
        }



    </script>

@endpush
