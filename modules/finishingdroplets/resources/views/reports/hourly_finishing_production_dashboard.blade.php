<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="refresh" content="30">
    <meta charset="utf-8"/>
    <title>goRMG | An Ultimate ERP Solutions For Garments</title>
    <meta name="description" content="Input Dashboard Report"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- for ios 7 style, multi-resolution icon of 152x152 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
    <meta name="apple-mobile-web-app-title" content="Flatkit">
    <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
    <!-- style -->
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet"
          href="{{ asset('modules/skeleton/flatkit/assets/material-design-icons/material-design-icons.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>
    <style>

        .table-inside {
            border: none;
            width: 100%;
        }

        .table-inside th {
            border-top: none;
        }

        .table-inside th:first-child {
            border-left: none;
        }

        .table-inside th:last-child {
            border-right: none;
        }

        .table-inside td {
            border: none;
            border-right: 1px solid #e7e7e7;
        }

        .table-inside td:last-child {
            border-right: none;
        }

        .table-inside tr {
            border-bottom: 1px solid #e7e7e7;
        }

        .table-inside tr:last-child {
            border-bottom: none;
        }

        .flex {
            display: flex;
        }

        .justify-content-center {
            justify-content: center;
        }

        .align-items-center {
            align-items: center;
        }

        .float-right {
            float: right;
        }

        .app-header,
        .navbar {
            height: 48px;;
        }

        .padding {
            padding: 0.5rem 1rem;
        }

        .parentTableFixed table > thead > tr > th {
            background-color: #97d9e1 !important;
        }

        .parentTableFixed::-webkit-scrollbar {
            width: 0.5em;
        }

        .parentTableFixed::-webkit-scrollbar-track {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        .parentTableFixed::-webkit-scrollbar-thumb {
            background-color: rgb(131, 199, 233);
            outline: 1px solid rgb(52, 164, 176);
        }

        #contain {
            height: 57vh;
            overflow-y: scroll;
        }

        #contain::-webkit-scrollbar {
            width: 0.5em;
        }

        #contain::-webkit-scrollbar-track {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        #contain::-webkit-scrollbar-thumb {
            background-color: rgb(131, 199, 233);
            outline: 1px solid rgb(52, 164, 176);
        }

        #table_scroll {
            width: 100%;
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
</head>

<body>
<div class="app" id="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                <div class="navbar text-center flex justify-content-center align-items-center">
                    <h4>Hourly Finishing Production Report || {{ date("jS F, Y") }}</h4>
                </div>
            </div>
        </div>
        <div class="app-footer">
            <div>
                @include('skeleton::partials.footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="padding">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-divider m-a-0"></div>
                            <div class="box-body">

                                <div id="parentTableFixed" class="table-responsive" style="max-height: 100%!important;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ############ PAGE END-->
        </div>
    </div>
</div>
<script src="{{ asset('modules/skeleton/lib/jquery/jquery.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/assets/table_head_fixer/tableHeadFixer.js') }}"></script>
<script>
    $(function () {
        $(".fixTable").tableHeadFixer();
    });

    let my_time;

    function pageScroll() {
        let objDiv = document.getElementById("contain");
        objDiv.scrollTop = objDiv.scrollTop + 1;
        if ((objDiv.scrollTop + 100) == objDiv.scrollHeight) {
            objDiv.scrollTop = 0;
        }
        my_time = setTimeout('pageScroll()', 25);
    }

    $(document).ready(function () {
        getReport();
        $(".pdf-excel-btn").click(function (event) {
            event.preventDefault();
            let date = $("#date").val();
            let url = $(this).attr("href");

            const urlParams = new URLSearchParams({date, floor_no: getURLParams().floor_no});

            url += `?${urlParams}`;

            window.location.assign(url);
        });
    });

    function getURLParams() {
        const urlParams = new URL(window.location.href);
        const floor_no = urlParams.searchParams.get('floor_no');
        return {floor_no};
    }

    function getReport() {
        let date = "{{date('Y-m-d')}}";

        const urlParams = new URLSearchParams({page: "view", floor_no: getURLParams().floor_no});

        $.ajax({
            url: `/hourly-finishing-production-report/get-report?${urlParams}`,
            type: "get",
            data: {date},
            dataType: "html",
            success(response) {
                $("#parentTableFixed").html(response);
            }
        });

    }
</script>
</body>
</html>
