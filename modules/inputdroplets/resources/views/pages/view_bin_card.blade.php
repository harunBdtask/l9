@extends('inputdroplets::layout')
@section('styles')
    <style type="text/css">
        @media print {
            .app-header ~ .app-body {
                padding: 0px !important;
            }

            .box-header-second {
                margin-top: 100px !important;
            }

            .no-print {
                display: none;
            }

            .reportTable thead, .reportTable tbody, .reportTable th {
                padding: 0px;
                font-size: 11px;
                text-align: center;
            }

            hr {
                margin-top: .0rem;
                margin-bottom: 0rem;
            }

            .box-header {
                padding: .25rem !important;
            }

            .second-part {
                display: block !important
            }

            .box-body {
                padding-top: 0rem !important;
            }

            .reportTable {
                margin-bottom: 0;
            }

            .single-challan-row:nth-of-type(2n) {
                margin-top: 70px !important;
            }

            .box-header-second {
                margin-top: 80px !important;
            }
        }

        .box-header {
            padding: .10rem !important;
        }

        .reportTable th,
        .reportTable td {
            border: 1px solid #000000;
        }
    </style>
@endsection
@section('title', 'Bin Card')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box" style="border: ridge;">
                    <div style="border: solid;">
                        <div style="border: ridge;">
                            <div class="single-challan-row">
                                <div class="box-header text-center">
                                    <span style="font-size: 18px; font-weight: bold;padding-left: 140px;"
                                          id="box-title">BIN CARD</span>
                                    <a class="btn btn-xs pull-right no-print"
                                       {{-- onclick="window.print();" --}} style="font-size: 17px;">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                    </a>
                                </div>
                                @php $image = factoryImage();
                                @endphp
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-lg-1">
                                            @if($image && File::exists('storage/factory_image/'.$image))
                                                <img src="{{ asset("storage/factory_image/$image")  }}"
                                                     alt="product_image" height="50"
                                                     width="50">
                                            @else
                                                <img src="{{ asset('images/no_image.jpg') }}" alt="no image"
                                                     height="50"
                                                     width="50">
                                            @endif
                                        </div>
                                        <div class="factory-area text-center col-lg-11" style="font-size: 1.1em;">
                                            <h4>{{ factoryName()}}</h4>
                                            <h5>{{ factoryAddress() }}</h5>
                                            <h6>Ready For Input</h6>
                                            <h5><b>BIN CARD</b></h5>
                                        </div>
                                    </div>


                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p style="font-size: 29px; font-weight: bolder">
                                                @if($product_images &&  File::exists('storage/'. $product_images)  )
                                                    <img src="{{ asset("storage/$product_images")  }}" alt=""
                                                         height="250"
                                                         width="250">
                                                @else
                                                    <img src="{{ asset('images/no_image.jpg') }}" alt="no image"
                                                         height="250"
                                                         width="250">
                                                @endif
                                            </p>
                                            <p style="font-size: 29px; font-weight: bolder">
                                                <span>&#8227;</span>
                                                BUYER NAME:
                                                <span>

                                          &nbsp;  {{ $inputBundles->unique('buyer_id')->implode('buyer.name', ', ') }}
                                        </span>
                                            </p>
                                            <p style="font-size: 29px; font-weight: bolder">
                                                <span>&#8227;</span>
                                                STYLE/ORDER NO:
                                                <span>
                                           &nbsp; {{ $inputBundles->unique('order_id')->implode('order.style_name', ', ') }}
                                        </span>
                                            </p>
                                            <p style="font-size: 29px; font-weight: bolder">
                                                <span>&#8227;</span>
                                                COLOR NAME:
                                                <span>
                                          &nbsp;  {{ $challan->color->name }}
                                        </span>
                                            </p>
                                            <p style="font-size: 29px; font-weight: bolder">
                                                <span>&#8227;</span>
                                                NUMBER OF BAG:
                                                <span>
                                            <input type="text"
                                                   style="width: 150px; border: 1px solid lightgrey;"
                                                   id="numberOfBag">
                                        </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="footer" hidden style="position: fixed; bottom: 0; width: 100%;">
                                @include('skeleton::reports.downloads.footer')
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
        $(function () {
            $('body').on('click', '.no-print', function () {
                $("#box-title").hide();
                $("#footer").removeAttr('hidden');
                $("#numberOfBag").attr("style", "width: 100px; border: 1px solid white;")
                window.print();
                $("#numberOfBag").attr("style", "width: 100px; border: 1px solid lightgrey;")
                $("#footer").attr('hidden', 'hidden');
                $("#box-title").show();
            });
        });
    </script>
@endpush
