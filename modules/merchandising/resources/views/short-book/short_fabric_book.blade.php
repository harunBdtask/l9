@extends('skeleton::layout')
@push('style')
    <style>

        table > tr > td {
            width: 100% !important;
        }

        .reportTable tr td {
            padding: 0px 0px;
        }

        .inner-table tr td {
            border-left: none;
            border-right: none;
        }

        /*.inner-table tr:last-child td{*/
        /*border-bottom: none;*/
        /*}*/

        .inner-table tr td {
            border-top: none;
        }

        .inner-table tr:last-child td {
            border-bottom: none;
        }

        /*.table thead tr:nth-child(even) {*/
        /*background: #f9f9f9 !important;*/
        /*}*/
        .data-count {
            padding: 0px;
            text-align: right;
            font-size: 14px;
        }

        table thead tr th {
            white-space: nowrap;
        }

        table tbody tr td {
            white-space: nowrap;
        }

        .right-align {
            padding-right: 10px;
        }

        .reportTable tbody th:last-child {
            width: 150px !important;
        }

        .select2-container--default .select2-selection--single {
            border-color: rgba(120, 130, 140, 0.2) !important;
            border-radius: 0px;
            height: 30px;
        }
        .in-print {
            display: none !important;
        }

        @media print {
            @page  {
                size: a4 landscape !important;
                margin-top: -7mm;
            ;

            }
            .reportTable th,
            .reportTable td {
                border: 1px solid #000;
            }
            .reportTable {
                margin-bottom: 1rem;
                width: 100%;
                max-width: 100%;
                font-size: 16px !important;
                border-collapse: collapse;
            }

            .in-print {
                display: block !important;
                margin-top: -15px !important;
            }


            .print-delete {
                display: none !important;
            }
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_recap_report_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'super-admin')
            <div class="box knit-card">
                <div class="row in-print">
                    <h4 class="text-center" style="font-size: 20px;padding: 0px">{{groupName()}}</h4>
                    <h6 class="text-center" style="font-size: 16px;margin-top: 0px">Unit: {{factoryName()}}</h6>
                    <p class="text-center">{{factoryAddress()}}</p>
                    <h4 class="text-center" style="margin-bottom: -5px;font-size: 16px">Fabric Short Booking Report</h4>
                    <br>
                </div>
                <div class="box-header print-delete">
                    <h2>Fabric Short Booking Report</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t print-delete" >
                    <div class="row print-delete">
                        <div class="col-md-8">
                            <form class="form-inline print-delete" method="GET" action="{{ url('short-fabric-book-search') }}" role="search">
                                <div class="form-group">
                                    {!! Form::selectMonth('month',request()->month ?? date('n'),['class'=>'form-control form-control-sm']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::selectRange('year', date('Y',strtotime('5 years ago')), date('Y',strtotime('+5 years')),request()->year ?? date('Y'),['class'=>'form-control form-control-sm']) !!}
                                </div>
                                <button type="submit" class="btn btn-sm white m-b">Search</button>
                            </form>
                        </div>
                        <div class="col-md-4 print-delete" align="right">
                            <ul>
                                <li style="list-style: none;display: inline-block"><a  id="print"><i class=" fa fa-print" ></i>&nbsp;Print</a></li>
                                <li style="list-style: none;display: inline-block"><a href="{{ url('short-fabric-book-pdf-download?month='.request()->month.'&month='.request()->month.'&year='.request()->year)}}" class="hidden-print btn btn-xs" title="Download this pdf"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix print-delete"></div>
                </div>
                <div class="box-body" style="margin-top: -15px">
                    <div>
                        {{--<p class="data-count">{{ $recap->firstItem() }} to {{ $recap->lastItem() }} of total {{$recap->total()}} entries</p>--}}
                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable" id="fixTable">
                                <thead>
                                <tr>
                                    <th>Booking No</th>
                                    <th>Fabric Short Booking</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shortBooking as $key => $shortBook)
                                    <tr>
                                        <td> {{ $key  }}</td>
                                        <td>{{ $shortBook }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--<div class="text-center print-delete"> {{$recap->render()}}</div>--}}
                            {{--<div class="text-center print-delete">{{$recap->appends($_GET)->links() }}</div>--}}
                        </div>

                    </div>
                </div>

            </div>
        @endif
    </div>

@endsection

@push('script-head')
    <script>
        $('body').on('click', '#print', function () {
            $('.box-body').find('div').removeClass('table-responsive');
            $('.print-delete').hide();
            window.print();
            $('.print-delete').show();
        });
    </script>
@endpush
@section('scripts')
    <script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#fixTable").tableHeadFixer();
        });
    </script>
@endsection
