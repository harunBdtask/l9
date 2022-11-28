@extends('hr::layout')
@section("title","Attendance Dashboard")
@section('content')

    <div class="padding">
        <div class="row">
            <h2 class="text-center m-y-2">Attendance Dashboard</h2>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row m-t">
                    <div class="col-md-4">
                        <div class="tile box cursor-pointer p-a success" data-toggle="tooltip"
                             data-placement="top"
                             title="Present Employee">
                            <div class="pull-left m-r">
                                <em class="fa fa-4x fa-user text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <h4 class="text-white">Present</h4>
                                <h4 id="present-employee" class="m-a-0 text-2x _600"><a>0</a></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="tile box cursor-pointer p-a bg-warning" data-toggle="tooltip"
                             data-placement="top"
                             title="Absent Employee">
                            <div class="pull-left m-r">
                                <em class="fa fa-4x fa-user text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <h4 class="text-white">Absent</h4>
                                <h4 id="absent-employee" class="m-a-0 text-2x _600"><a>0</a></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="tile box cursor-pointer p-a danger" data-toggle="tooltip"
                             data-placement="top"
                             title="Late Employee">
                            <div class="pull-left m-r">
                                <em class="fa fa-4x fa-user text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <h4 class="text-white">Late</h4>
                                <h4 id="late-employee" class="m-a-0 text-2x _600"><a>0</a></h4>
                            </div>
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

            setInterval(function() {
                $.ajax({
                    url: `/hr/attendance/attendance-by-date`,
                    type: 'get',
                    success: function (response) {
                        $("#present-employee").html(response.presentEmployee);
                        $("#absent-employee").html(response.absentEmployee);
                        $("#late-employee").html(response.lateEmployee);
                    }
                });
            }, 1000);

        })



    </script>

@endpush
