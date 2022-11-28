@extends('washingdroplets::layout')
@section('title', 'Washing Scan')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Washing Scan || {{ date("jS F, Y") }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                       <div class="row">
                           <div class="col-lg-12">
                               @include('partials.response-message')
                               <form method="POST" action="{{ url('/washing-scan-post')}}" accept-charset="UTF-8">
                                   @csrf

                                   <input type="hidden" name="washing_challan_no" value="{{ $washing_challan_no ?? '' }}">
                                   <input type="hidden" name="washing_challan_no" value="{{ $washing_challan_no ?? '' }}">

                                   <div class="col-sm-8 col-sm-offset-2">
                                       <div class="form-group">
                                           <input type="text" class="form-control form-control-sm has-value" id="barcode"
                                                  placeholder="Scan barcode here" autofocus="" name="barcode" required="required">
                                           <span> challan no: {{ $washing_challan_no }}</span>
                                       </div>
                                   </div>

                                   @if(count($washing_bundles))
                                       <div class="form-group m-t-md">
                                           <div class="col-sm-offset-5 col-sm-7">
                                               <a href="{{ url('/send-sewing-to-wash/'.$washing_challan_no)}}"
                                                  class="btn btn-success">Send To Wash</a>
                                           </div>
                                       </div>
                                   @endif
                               </form>

                               @if(count($washing_bundles))
                                   <table class="reportTable">
                                       <thead>
                                       <tr>
                                           <th>Buyer</th>
                                           <th>Style</th>
                                           <th>PO</th>
                                           <th>Colour</th>
                                           <th>Size</th>
                                           <th>Bundle No.</th>
                                           <th>Quantity</th>
                                       </tr>
                                       </thead>
                                       <tbody class="print-sent-list">
                                       @php $total = 0; @endphp
                                       @foreach($washing_bundles as $bundle)
                                           @php
                                               $bundleQty = $bundle->bundlecard->quantity
                                                 - $bundle->bundlecard->total_rejection
                                                 - $bundle->bundlecard->print_rejection
                                                 - $bundle->bundlecard->sewing_rejection;
                                               $total += $bundleQty;
                                           @endphp
                                           <tr>
                                               <td>{{ $bundle->buyer->name ?? '' }}</td>
                                               <td>{{ $bundle->order->style_name ?? '' }}</td>
                                               <td>{{ $bundle->purchaseOrder->po_no ?? '' }}</td>
                                               <td>{{ $bundle->bundlecard->color->name ?? '' }}</td>
                                               <td>{{ $bundle->bundlecard->size->name ?? '' }}</td>
                                               <td>{{ str_pad($bundle->bundlecard->size_wise_bundle_no ?? $bundle->bundlecard->bundle_no , 2, '0', STR_PAD_LEFT) }}</td>
                                               <td>{{ $bundleQty }}</td>
                                           </tr>
                                       @endforeach
                                       <tr>
                                           <td colspan="5" class="text-right"><b>Total :</b></td>
                                           <td><b>{{ str_pad(count($washing_bundles), 3, '0', STR_PAD_LEFT) }}</b></td>
                                           <td><b>{{ $total }}</b></td>
                                       </tr>
                                       </tbody>
                                   </table>
                               @endif
                           </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
