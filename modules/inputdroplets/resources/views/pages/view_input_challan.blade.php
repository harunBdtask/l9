@extends('inputdroplets::layout')
@section('styles')
    <style>
        @media print {
            .app-header ~ .app-body {
                padding: 0 !important;
            }

            .box-header-second {
                margin-top: 100px !important;
            }

            .no-print {
                display: none;
            }

            .reportTable thead, .reportTable tbody, .reportTable th {
                padding: 0;
                font-size: 11px;
                text-align: center;
            }

            hr {
                margin-top: .0rem;
                margin-bottom: 0;
            }

            .box-header {
                padding: .25rem !important;
            }

            .second-part {
                display: block !important
            }

            .box-body {
                padding-top: 0 !important;
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
@section('title', 'Input Challan')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="single-challan-row">
                        <div class="box-header text-center">
                            <span style="font-size: 18px; font-weight: bold;">Input Challan</span>
                            <a class="btn btn-xs pull-right no-print"
                               {{-- onclick="window.print();" --}} style="font-size: 17px;">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="box-divider m-a-0"></div>
                        <div class="box-body">
                            @if($factory)
                                <div class="factory-area text-center" style="font-size: 1.1em;">
                                    <strong>{{ $factory->factory_name}}</strong><br>
                                    {{ $factory->factory_address }}<br>
                                </div>
                            @endif
                            <hr>
                            <div class="col-sm-12" style="margin-bottom: 20px;">
                                <div class="row">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-4">
                                        <strong>Color : </strong> {{ $challan->color->name ?? '' }}
                                        <br> <strong>C/N : </strong> {{ implode(", ", $cuttingNumbers) }}
                                        <br> <strong>Lot : </strong> {{ implode(", ", $lots) }}
                                        @if($challan->total_rib_size)
                                            <br> <strong>Rib/Piping : </strong> {{ $challan->total_rib_size }} Kg
                                        @endif
                                    </div>
                                    <div class="col-sm-2">
                                        <strong>Sending To:</strong>
                                    </div>
                                    <div class="col-sm-5">
                                        @php
                                            $new_challan_time = '';
                                            if (isset($challan)) {
                                              $challan_originial_time = $challan->updated_at;
                                              if (date('H', strtotime($challan_originial_time)) < 8) {
                                                $new_challan_time = '08:'.date('i:s', strtotime($challan_originial_time));
                                              } elseif (date('H', strtotime($challan_originial_time)) >= 19) {
                                                $new_challan_time = '18:'.date('i:s', strtotime($challan_originial_time));
                                              } else {
                                                $new_challan_time = date('H:i:s', strtotime($challan_originial_time));
                                              }
                                            }
                                        @endphp
                                        <strong>Floor No : </strong> {{ $challan->line->floor->floor_no ?? '' }}
                                        <br> <strong>Line No : </strong> {{ $challan->line->line_no ?? '' }}
                                        <br> <strong>Challan No : </strong> {{ $challan->challan_no }}
                                        <br> <strong>Date
                                            : </strong> {{ date('jS F, Y', strtotime($challan->updated_at)) }}
                                        <br> <strong>Time
                                            : </strong> {{ \Carbon\Carbon::make($new_challan_time)->format('H:i:s a') }}
                                    </div>
                                </div>
                            </div>

                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th style="background-color: aliceblue">Buyer</th>
                                    <th style="background-color: aliceblue">Style</th>
                                    <th style="background-color: aliceblue">Item</th>
                                    <th style="background-color: aliceblue">PO</th>
                                    <th style="background-color: aliceblue">PO Qty</th>
                                    <th style="background-color: aliceblue">Size</th>
                                    <th style="background-color: aliceblue">Total Bundle</th>
                                    <th style="background-color: aliceblue">Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($inputBundles)
                                    @php
                                        $totalQty = 0;
                                        $totalBundle = 0;
                                    @endphp
                                    @foreach ($inputBundles->sortBy('buyer_id')->groupBy('buyer_id') as $buyerWsie)
                                        @foreach ($buyerWsie->sortBy('order_id')->groupBy('order_id') as $orderStyleWise)
                                            @foreach ($orderStyleWise->sortBy('purchase_order_id')->groupBy('purchase_order_id') as $purchaseOrderWise)
                                                @if($poWiseSizeDetails && count($poWiseSizeDetails) && array_key_exists($purchaseOrderWise->first()->purchase_order_id, $poWiseSizeDetails))
                                                  @foreach($poWiseSizeDetails[$purchaseOrderWise->first()->purchase_order_id] as $key => $sizeId)
                                                    @if(!$purchaseOrderWise->where('size_id', $sizeId)->count())
                                                      @continue
                                                    @endif
                                                    @foreach ($purchaseOrderWise->where('size_id', $sizeId)->groupBy('size_id') as $sizeKey => $sizeBundles)
                                                      @php
                                                          $singleBundle = $sizeBundles->first();
                                                          $sizeWiseQty = $sizeBundles->sum('quantity')
                                                            - $sizeBundles->sum('total_rejection')
                                                            - $sizeBundles->sum('print_embroidary_rejection');
                                                          $totalQty += $sizeWiseQty;
                                                          $totalBundle += collect($sizeBundles)->count();
                                                      @endphp
                                                      <tr>
                                                          <td>{{ $singleBundle->buyer->name ?? '' }}</td>
                                                          <td>{{ $singleBundle->order->style_name ?? '' }}</td>
                                                          <td>{{ $singleBundle->garmentsItem->name ?? '' }}</td>
                                                          <td>{{ $singleBundle->purchaseOrder->po_no ?? '' }}</td>
                                                          <td>{{ $singleBundle->purchaseOrder->po_quantity ?? '' }}</td>
                                                          <td>{{ $singleBundle->size->name ?? '' }}</td>
                                                          <td>{{ collect($sizeBundles)->count() }}</td>
                                                          <td>{{ $sizeWiseQty }}</td>
                                                      </tr>
                                                    @endforeach
                                                  @endforeach
                                                @else
                                                  @foreach ($purchaseOrderWise->sortBy('size_id')->groupBy('size_id') as $sizeKey => $sizeBundles)
                                                      @php
                                                          $singleBundle = $sizeBundles->first();
                                                          $sizeWiseQty = $sizeBundles->sum('quantity')
                                                            - $sizeBundles->sum('total_rejection')
                                                            - $sizeBundles->sum('print_embroidary_rejection');
                                                          $totalQty += $sizeWiseQty;
                                                          $totalBundle += collect($sizeBundles)->count();
                                                      @endphp
                                                      <tr>
                                                          <td>{{ $singleBundle->buyer->name ?? '' }}</td>
                                                          <td>{{ $singleBundle->order->style_name ?? '' }}</td>
                                                          <td>{{ $singleBundle->purchaseOrder->po_no ?? '' }}</td>
                                                          <td>{{ $singleBundle->purchaseOrder->po_quantity ?? '' }}</td>
                                                          <td>{{ $singleBundle->size->name ?? '' }}</td>
                                                          <td>{{ collect($sizeBundles)->count() }}</td>
                                                          <td>{{ $sizeWiseQty }}</td>
                                                      </tr>
                                                  @endforeach
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                    <tr style="font-weight: bold">
                                        <td style="background-color: gainsboro" colspan="6" class="text-right">
                                            <strong>Total :</strong></td>
                                        <td style="background-color: gainsboro">{{ $totalBundle }}</td>
                                        <td style="background-color: gainsboro">{{ $totalQty }}</td>
                                    </tr>
                                @else
                                    <tr class="te-height">
                                        <td colspan="7" class="text-center text-danger">
                                            Not found
                                        <td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                            @if(request('version') == 'v2')
                                <div style="width: 100%; margin-top: 20px">
                                    <div style="width: 45%;float: right;">
                                        <table class="reportTable">
                                            <thead>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div style="width: 45%;">
                                        <table class="reportTable">
                                            <thead>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 50%"><b>SWING THREAD</b></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td><b>MAIN LABEL</b></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td><b>CARE LABEL</b></td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($inputBundles && $challan)
                              @php
                                $imgPath = $challan->user->signature;
                                $imgSrc = null;
                                if ($imgPath && Storage::disk('public')->exists($imgPath)) {
                                  $imgSrc = asset('storage/'.$imgPath);
                                }

                                $cutPath = $challan->cutManager->signature;
                                $cutImgSrc = null;
                                if ($cutPath && Storage::disk('public')->exists($cutPath)) {
                                  $cutImgSrc = asset('storage/'.$cutPath);
                                }

                              @endphp
                                <table class="text-center" style="width:100%; margin-top: 48px; margin-bottom: 0">
                                    <tr style="font-weight: bold; font-size: 12px">
                                        <td></td>
                                        <td>
                                          @if($imgSrc)
                                            <img src="{{ $imgSrc }}" alt="signature" style="width: 140px; height:auto;">
                                          @endif
                                        </td>
                                        <td></td>
                                        <td>
                                            @if($cutImgSrc)
                                                <img src="{{ $cutImgSrc }}" alt="signature" style="width: 140px; height:auto;">
                                            @endif
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr style="font-weight: bold; font-size: 12px">
                                        <td style="width:5%"></td>
                                        <td>Prepared By</td>
                                        <td>Input Supervisor</td>
                                        <td>Cutting Incharge/Manager</td>
                                        <td>Received By</td>
                                        <td>Store</td>
                                    </tr>
                                </table>
                            @endif
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
                let challan = $(this).closest('.single-challan-row').clone();
                let version = '{{ request('version') }}'
                if(!version) {
                    $('.box').append(challan);
                }
                window.print();
                $(".single-challan-row").not(':first').remove();
            });
        });
    </script>
@endpush