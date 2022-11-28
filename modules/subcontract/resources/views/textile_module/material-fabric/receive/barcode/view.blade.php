@extends('skeleton::layout')
@section('title','Material Fabric Receive Barcode')
@section('styles')
    <style>
        .flex {
            display: flex;
        }

        .flex-row {
            flex-direction: row;
        }

        .justify-content-center {
            justify-content: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .barcode-container p {
            margin-bottom: 0.15rem !important;
        }

        .font-600 {
            font-weight: 600;
        }

        @media print {
            html,
            body {
                height: 99%;
            }

            body {
                margin: 0;
                padding: 0 !important;
                min-width: 768px;
                font-size: 16px !important;
            }

            a[href]:after {
                content: none;
            }

            .noprint {
                display: none;
            }

            .padding,
            .app-header,
            .app-body {
                margin: 0;
                padding: 0 !important;
            }

            .barcode-container {
                margin-bottom: 14px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        @foreach($receive->receiveDetails as $detailKey => $detail)
            @includeIf('subcontract::textile_module.material-fabric.receive.barcode.view_body')
        @endforeach
    </div>
@endsection
