@extends('skeleton::layout')
@push('style')
    <style>
        @media print {
            @page {
                size: a4 landscape !important;
                margin-top: -27mm !important;
            }

            .print-delete {
                display: none !important;
            }

        }
    </style>
@endpush
@section('content')
    <div class="row">
    <div class="box section-to-print col-sm-6 col-sm-offset-3">
        <div class="box-header text-center dker m-t-1">
            <h2>{{ factoryName() }}</h2>
            <h4 class="text-dark m-t-1">{{factoryAddress()}}</h4>
            <h3 class="m-t-1"><b> VISITOR CARD</b></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-4">
                    <p><b>Name</b></p>
                    <p><b>Registration No</b></p>
                    <p><b>Designation</b></p>
                    <p><b>Company</b></p>
                    <p><b>Mobile</b></p>
                    <p><b>Email</b></p>
                    <p><b>Meeting person</b></p>
                </div>
                <div class="col-xs-4">
                    <p>{{$visitor->name ?? ''}}</p>
                    <p>{{$visitor->registration_id ?? ''}}</p>
                    <p>{{$visitor->designation ?? ''}}</p>
                    <p>{{ $visitor->company_name ?? '' }}</p>
                    <p>{{$visitor->mobile_number ?? ''}}</p>
                    <p>{{$visitor->email ?? ''}}</p>
                    <p>{{$visitor->meeting_person ?? ''}}</p>
                </div>
                <div class="col-xs-4">
                    <div class="row">
                        <div class="text-center">
                            {!! QrCode::size(130)->generate($visitor->registration_id); !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer dker text-center">
                <small>©Copyright - goRMG. Produced by Skylark Soft Limited.</small>

            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="m-b text-center">
            <button class="md-btn md-raised m-b-sm w-xs indigo print-delete" id="print">Print</button>
        </div>
    </div>

@endsection
@push('script-head')
    <script>
        $('#print').on('click', function () {
            $('.section-to-print').removeClass('col-sm-offset-3').addClass('m-y-1 m-x-1');
            window.print();
            $('.section-to-print').addClass('col-sm-offset-3').removeClass('m-y-1 m-x-1');
        });
    </script>
@endpush