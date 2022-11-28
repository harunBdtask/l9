@extends('skeleton::layout')
@section("title","Audit")
@section('content')
    <section class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>Audit</h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-9">
                        <a href="{{ url('/audits/excel') }}?date={{ $date }}" class="btn btn-sm btn-primary"><i
                                class="fa fa-file-excel-o"></i></a>
                        {{--                        <a href="" class="btn btn-sm btn-warning"><i class="fa fa-file-pdf-o"></i></a>--}}
                    </div>
                    <div class="col-sm-3">
                        <form action="" method="GET">
                            <div class="input-group">
                                <input type="date" class="form-control" name="date"
                                       value="{{ $date }}" placeholder="Search">
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="submit">Search</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        @includeIf('system-settings::audit.data')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
