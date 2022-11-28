@extends('skeleton::layout')
@section('title','Primary Contract')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Primary Contract
                    <i class="fa fa-file-pdf-o btn" style="float:right;cursor: pointer" id="getPdf"
                       data-id="{{ $contracts->id }}"></i>
                </h2>
            </div>
            
            @include('commercial::primary-master-contract.view-body')

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#getPdf").click(function () {
            const id = $(this).data('id');
            const url = '/commercial/primary-master-contract/' + id + '/pdf';
            location.assign(url);
        });
    </script>
@endsection
