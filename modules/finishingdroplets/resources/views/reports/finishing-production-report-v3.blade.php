@extends('finishingdroplets::layout')
@section('title', 'Finishing Production V3')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Finish Production Report V3 || {{ date("jS, F Y") }}<span class="pull-right">
{{--                                <a download-type="pdf" class="finishing-receieved-report-dwnld-btn"><i--}}
{{--                                        style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> |--}}
{{--                                <a download-type="xls" class="finishing-receieved-report-dwnld-btn"><i--}}
{{--                                        style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>--}}
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body color-finishing">
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-2">
                                    <label>From</label>
                                    {!! Form::date('from', date('Y-m-d'), [
                                    'class' => 'form-control form-control-sm',
                                    'id' => 'from',
                                    ]) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>To</label>
                                    {!! Form::date('to', date('Y-m-d'), [
                                    'class' => 'form-control form-control-sm',
                                    'id' => 'to',
                                    ]) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>Buyer</label>
                                    {!! Form::select('buyer_id', $buyers, null, [
                                    'class' => 'form-control form-control-sm select2-input',
                                    'id' => 'buyer_id',
                                    'placeholder' => 'Select Buyer',
                                    ]) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>Finish Floor</label>
                                    {!! Form::select('finish_floor_id', $finishFloors, null, [
                                        'class' => 'form-control form-control-sm select2-input',
                                        'id' => 'finish_floor_id',
                                        'placeholder' => 'Select Finish Floor',
                                        ]) !!}
                                </div>
                                <div class="col-sm-2">
                                    <label>Sewing Floor</label>
                                    {!! Form::select('sewing_floor_id', $sewingFloors, null, [
                                            'class' => 'form-control form-control-sm select2-input',
                                            'id' => 'sewing_floor_id',
                                            'placeholder' => 'Select Sewing Floor',
                                            ]) !!}
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-success btn-sm m-t-2" type="button" id="search">
                                        <em class="fa fa-search"></em> Search
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div id="parentTableFixed" class="table-responsive report-body">
                            {{--                            TODO--}}
                        </div>
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#search").click(() => {
            const from = $("#from").val();
            const to = $("#to").val();
            const buyer_id = $("#buyer_id").val();
            const finish_floor_id = $("#finish_floor_id").val();
            const sewing_floor_id = $("#sewing_floor_id").val();

            $.ajax({
                url: '/finishing-production-report/v3/report',
                type: 'POST',
                data: {from, to, buyer_id, finish_floor_id, sewing_floor_id},
                dataType: 'html',
                success(res) {
                    $(".report-body").html(res);
                }
            })
        })
    </script>
@endsection
