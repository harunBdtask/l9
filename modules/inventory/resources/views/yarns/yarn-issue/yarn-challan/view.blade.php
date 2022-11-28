@extends('skeleton::layout')
@section('title','Yarn Issue Challan')
@section('content')
    <style>
        .v-align-top td,
        .v-algin-top th {
            vertical-align: top;
        }

        #factoryName {
            display: block !important;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .header-section {
            padding: 10px;
        }

        .view-body {
            margin-top: 25px;
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        #logo {
            display: none;
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-body">
                <div class="header-section" style="padding-bottom: 0px;">
                    <div class="pull-right" style="margin-bottom: -5%;">
{{--                        <a class="btn print"--}}
{{--                           href="{{url('inventory/yarn-issue/challan/'.$yarnIssue->id.'/yarn-challan-print')}}">--}}
{{--                            <i class="fa fa-print"></i>--}}
{{--                        </a>--}}
                        <a class="btn"
                           href="{{url('inventory/yarn-issue/challan/'.$yarnIssue->id.'/yarn-challan-pdf')}}">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="view-body">
                @include('inventory::yarns.yarn-issue.yarn-challan.table')
            </div>
        </div>
    </div>
@endsection
@push("script-head")
    <script>
        $(document).ready(function () {
            $('.print').click(function (e) {
                e.preventDefault();
                var url = $(this).attr('href');

                printPage(url);
            });

            function closePrint() {
                document.body.removeChild(this.__container__);
            }

            function setPrint() {
                this.contentWindow.__container__ = this;
                this.contentWindow.onbeforeunload = closePrint;
                this.contentWindow.onafterprint = closePrint;
                this.contentWindow.focus(); // Required for IE
                this.contentWindow.print();
            }

            function printPage(sURL) {
                var oHiddFrame = document.createElement("iframe");
                oHiddFrame.onload = setPrint;
                oHiddFrame.style.visibility = "hidden";
                oHiddFrame.style.position = "fixed";
                oHiddFrame.style.right = "0";
                oHiddFrame.style.bottom = "0";
                oHiddFrame.src = sURL;
                document.body.appendChild(oHiddFrame);
            }
        });
    </script>
@endpush
