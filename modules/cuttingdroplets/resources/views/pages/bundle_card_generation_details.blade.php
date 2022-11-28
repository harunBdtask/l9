@extends('cuttingdroplets::layout')

@section('title', 'Bundle Card')
@section('styles')
<style type="text/css">
  .bundle-cards table,
  .bundle-cards th,
  .bundle-cards td {
    border: 1px solid black !important;
  }

  .bundle-card-generation-details table,
  .bundle-card-generation-details th,
  .bundle-card-generation-details td {
    border: 1px solid black !important;
  }

  .bundle-cards td.third {
    display: none;
  }

  #consumptionInfo div[class^="col-"] {
    padding-right: 3px !important;
    padding-left: 3px !important;
  }

  .barcode {
    padding-top: 6px !important;
  }

  .table-borderlss tr,
  .table-borderlss td,
  .table-borderlss th {
    border: 1px solid transparent!important;
    font-size: smaller;
    text-align: left;
  }

  .table-condensed thead > tr > th, .table-condensed tbody > tr > th, .table-condensed tfoot > tr > th, .table-condensed thead > tr > td, .table-condensed tbody > tr > td, .table-condensed tfoot > tr > td {
    padding: 1px;
  }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('css/print.css') }}">
@endsection

@php
error_reporting(0);
$bundle_card_sticker_ratio_view_status = getBundleCardStickerRatioViewStatus();
@endphp

@section('content')
<div class="padding">
  <div class="box AllContent hide">
    <div class="box-header">
      <div class="row">
        <div class="col-md-4">
          @if(getBundleCardPrintStyle() == 2)
          <table class="table table-condensed table-borderlss">
            <thead>
              <tr>
                <th>Document's No: GMS/CUTTING/(FR)-02</th>
              </tr>
              <tr>
                <th>Department: Cutting</th>
              </tr>
            </thead>
          </table>
          @endif
        </div>
        <div class="col-md-4">
          <h4 class="text-center">{{ $bundleCardGenerationDetail['factory']['factory_name'] ?? '' }}</h4>
          <h4 class="text-center">{{ $bundleCardGenerationDetail['factory']['factory_address'] ?? '' }}</h4>
          <h4 class="text-center">{{ 'Barcode Enabled Bundle Card Generator' }}</h4>
          {{--  <h4 class="text-center">{{ 'Unit: '.($bundleCardGenerationDetail->factory->factory_name ?? '') }}</h4> --}}
        </div>
        <div class="col-md-4">
          @if(getBundleCardPrintStyle() == 2)
          <table class="table table-condensed table-borderlss">
            <thead>
              <tr>
                <th colspan="4">Document's Name : Barcode Enabled Bundle Card Generator</th>
              </tr>
              <tr>
                <th colspan="2">Issue/Version No : 1.0</th>
                <th colspan="2">Issue Date : 1-March-2022</th>
              </tr>
              <tr>
                <th>Revised No :</th>
                <th>&nbsp;</th>
                <th>Revised Date :</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
          </table>
          @endif
        </div>
      </div>
    </div>
    <div class="box-header">
      <div class="row">
          <div class="col-sm-12">
              @if(session('failure'))
              <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <p>{{ session('failure') }}</p>
              </div>
              @enderror
          </div>
        <div class="col-sm-4 pull-left">
          <h2>Session No. : {{ str_pad($bundleCardGenerationDetail['sid'], 4, "0", STR_PAD_LEFT) }}</h2>
          {!! $bundleCardGenerationDetail['created_at'] ? '<br><h2>Date: '.Date('d/m/Y', strtotime($bundleCardGenerationDetail['created_at'])).'</h2>' : '' !!}
        </div>
        <div class="col-sm-4 pull-right">
          <div class="pull-right">
            @if($bundleCards->count() > 0)
            <a href="{{ url('bundle-card-generations/'.$bundleCardGenerationDetail['id'].'/re-generate') }}"
              class="btn btn-sm white m-b"><i class="fa fa-retweet"></i> Regenerate</a>
            @endif
            <button class="btn btn-sm white m-b print-btn" type="button">
              <i class="fa fa-print"></i> Print
            </button>
          </div>
          {!! Form::open(['url' => 'bundle-card-generations/'.$bundleCardGenerationDetail['id'].'/scan', 'method' =>
          'POST', 'onsubmit' => "this.style.visibility = 'hidden'"]) !!}
          @if($bundleCards->count() > 0 && $bundleCards->sum('status') == 0)
          <button class="btn btn-sm white m-b pull-right" type="submit" style="margin-right: 5px;">
            <i class="fa fa-bolt"></i> Scan Now
          </button>
          @endif
          {!! Form::close() !!}
        </div>
      </div>
    </div>
    @php
    error_reporting(0);
    $garmentsItem = $bundleCardGenerationDetail['garments_item']['name'] ?? $bundleCardGenerationDetail['garmentsItem']['name'] ?? '';
    @endphp
    <div class="box-body b-t bundle-card-generation-details">
      <div class="row" id="consumptionInfo">
        <div class="col-sm-3">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Roll</th>
                <th class="text-center">Ply</th>
                <th class="text-center">Weight</th>
                <th class="text-center">Dia</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bundleCardGenerationDetail['rolls'] as $roll)
              <tr>
                <td align="center">{{ $roll['roll_no'] }}</td>
                <td align="center">{{ $roll['plys'] }}</td>
                <td align="center">{{ number_format($roll['weight'], 2) }}</td>
                <td align="center">{{ number_format($roll['dia'], 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Total Ply</th>
                <th class="text-center">Total Weight</th>
                {{-- <th class="text-center">Total Dia</th> --}}
                <th class="text-center">Avg. Dia</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_ply'] }}</td>
                <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_weight'] }}</td>
                {{-- <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_dia'] }}</td> --}}
                <td align="center">{{ number_format($bundleCardGenerationDetail['roll_summary']['average_dia'], 2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-3">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Serial</th>
                <th class="text-center">Size</th>
                <th class="text-center">Suffix</th>
                <th class="text-center">Ratio</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bundleCardGenerationDetail['ratios'] as $ratio)
              <tr>
                <td align="center">{{ $ratio['serial_no'] }}</td>
                <td align="center">{{ $ratio['size_name'] }}</td>
                <td align="center">{{ $ratio['suffix'] }}</td>
                <td align="center">{{ $ratio['ratio'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Marker Piece</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td align="center">{{ $bundleCardGenerationDetail['marker_piece'] }}</td>
              </tr>
            </tbody>
          </table>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Lot No</th>
                <th class="text-center" colspan="2">Range</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bundleCardGenerationDetail['lot_ranges'] as $lot_range)
              <tr>
                <td align="center">{{ $lots->where('id', $lot_range['lot_id'])->first()->lot_no ?? '' }}</td>
                <td align="center">{{ $lot_range['from'] }}</td>
                <td align="center">{{ $lot_range['to'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
            <table class="table table-bordered table-sm">
                <thead>
                <tr>
                    <th></th>
                    <th>DIA</th>
                    <th>GSM</th>
                    <th>CONS</th>
                    <th>COMMENTS</th>
                    <th>RESULT</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>BOOKING</td>
                    <td>{{ $summaryReport['booking']['dia'] }}</td>
                    <td>{{ $summaryReport['booking']['gsm'] }}</td>
                    <td>{{ $summaryReport['booking']['consumption'] }}</td>
                    <td rowspan="3">{{ $summaryReport['comments'] }}</td>
                    <td rowspan="3">{{ $summaryReport['result'] }}</td>
                </tr>
                <tr>
                    <td>ACTUAL</td>
                    <td>{{ $summaryReport['actual']['dia'] }}</td>
                    <td>{{ $summaryReport['actual']['gsm'] }}</td>
                    <td>{{ $summaryReport['actual']['consumption'] }}</td>
                </tr>
                <tr>
                    <td>DEVIATION</td>
                    <td>{{ $summaryReport['deviation']['dia'] }}</td>
                    <td>{{ $summaryReport['deviation']['gsm'] }}</td>
                    <td>{{ $summaryReport['deviation']['consumption'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-3">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th width="7%">SL</th>
                <th width="15%">Size</th>
                <th width="15%">Bundles</th>
                <th width="15%">Quantity</th>
              </tr>
            </thead>
            <tbody>
            @php
            $totalBundles = 0;
            @endphp
              @foreach($bundleCardGenerationDetail['bundle_summary']['bundle'] as $summary)
              <tr>
                <td align="center">{{ $summary['serial'] }}</td>
                <td align="center">{{ $summary['size'] . ($summary['suffix'] ? '('.$summary['suffix'].')' : '') }}</td>
                @if(isMaxBundleQtyEnabled())
                    @php
                        $bundles = ceil($summary['quantity'] / $bundleCardGenerationDetail['max_quantity']);
                        $totalBundles += $bundles;
                    @endphp
                      <td align="center">{{ $bundles }}</td>
                @else
                  <td align="center">{{ $summary['bundles'] }}</td>
                @endif
                <td align="center">{{ $summary['quantity'] }}</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="2" align="center"><strong>Total</strong></td>
                  @if(isMaxBundleQtyEnabled())
                      <td align="center"><strong>{{ $totalBundles }}</strong>
                      </td>
                  @else
                      <td align="center"><strong>{{ $bundleCardGenerationDetail['bundle_summary']['total_bundle'] }}</strong>
                      </td>
                  @endif
                <td align="center">
                  <strong>{{ $bundleCardGenerationDetail['bundle_summary']['total_quantity'] }}</strong>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-3">
          <table class="table table-bordered table-sm">
            <tbody>
              <tr>
                <td align="left"><strong>Max Qty / Bundle</strong></td>
                <td align="left">{{ $bundleCardGenerationDetail['max_quantity'] }}</td>
              </tr>
              <tr>
                <td align="left"><strong>Booking Consumption</strong></td>
                <td align="left">{{ $bundleCardGenerationDetail['booking_consumption'] }}</td>
              </tr>
              <tr>
                <td align="left"><strong>Booking Dia</strong></td>
                <td align="left">{{ $bundleCardGenerationDetail['booking_dia'] }}</td>
              </tr>
              <tr>
                <td align="left"><strong>Buyer Name</strong></td>
                <td align="left">{{ $bundleCardGenerationDetail['buyer']['name'] ?? '' }}</td>
              </tr>
              <tr>
                <td align="left"><strong>{{ localizedFor('Style') }}</strong></td>
                <td align="left">{{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}</td>
              </tr>
              @if(isset($bundleCardGenerationDetail['order']['reference_no']))
                <tr>
                  <td align="left"><strong>Ref. No.</strong></td>
                  <td align="left">{{ $bundleCardGenerationDetail['order']['reference_no'] ?? '' }}</td>
                </tr>
              @endif
              <tr>
                  <td align="left"><strong>{{ localizedFor('PO') }}</strong></td>
                  <td align="left">{{ $pos ?? '' }}</td>
              </tr>
              <tr>
                <td align="left"><strong>Item</strong></td>
                <td align="left">{{ $garmentsItem }}</td>
              </tr>
              <tr>
                <td align="left"><strong>Color</strong></td>
                <td align="left">{{ $colors ?? '' }}</td>
              </tr>
              <tr>
                <td align="left"><strong>Cutting No.</strong></td>
                <td align="left">
                  @php
                  $cuttingNo = $bundleCardGenerationDetail['cutting_no'];

                  if ($bundleCardGenerationDetail['colors']) {
                  $cuttingNosWithColor = explode('; ', $cuttingNo);

                  $cuttingNo = '';
                  foreach ($cuttingNosWithColor as $cuttingNoWithColor) {
                  $cutting = explode(': ', $cuttingNoWithColor);
//                  $cuttingNo .= \SkylarkSoft\GoRMG\SystemSettings\Models\Color::findOrFail($cutting[0])->name . ': ' .
                  $cuttingNo .= $cutting[1] . '; ';
                  }
                  $cuttingNo = rtrim($cuttingNo, '; ');
                  }
                  @endphp
                  {{ $cuttingNo }}
                </td>
              </tr>
              <tr>
                <td align="left"><strong>Cutting Table</strong></td>
                <td align="left">
                  {{ $bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ??'' }}
                </td>
              </tr>
              <tr>
                <td align="left"><strong>Part</strong></td>
                <td align="left">{{ $bundleCardGenerationDetail['part']['name'] ?? '' }}</td>
              </tr>
              <tr>
                <td align="left"><strong>Type</strong></td>
                <td align="left">{{ $bundleCardGenerationDetail['type']['name'] ?? '' }}</td>
              </tr>
            </tbody>
          </table>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Total No. Of Roll</th>
                <th class="text-center">Total Cutting Qty</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_roll'] }}</td>
                <td align="center">{{ $bundleCardGenerationDetail['bundle_summary']['total_quantity'] }}</td>
              </tr>
            </tbody>
          </table>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Total Weight</th>
                <th class="text-center">Total Cutting Should Be</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_weight'] }} Kg</td>
                <td align="center">
                  {{ number_format($bundleCardGenerationDetail['total_cutting_quantity_should_be'], 2) . ' PCs' }}</td>
              </tr>
            </tbody>
          </table>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Quantity Save/Loss</th>
                <th class="text-center">Used Consumption</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td align="center">{{ number_format($bundleCardGenerationDetail['quantity_save_or_loss'], 2) }} PCs</td>
                <td align="center">{{ number_format($bundleCardGenerationDetail['used_consumption'], 2) }} Kg/Dz</td>
              </tr>
            </tbody>
          </table>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center">Consumption Save Or Loss</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td align="center">{{ number_format($bundleCardGenerationDetail['consumption_save_or_loss'], 2) }}Kgs
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <hr class="noprint">
      <div class="newpage"></div>
      <br>
      <div class="row">
        @foreach($stickers as $sticker)
        @php
        $sticker = collect($sticker)->sortBy('sorting');
        @endphp
        <div class="col-md-4">
          <div>
            <p class="text-center" style="margin-bottom: 5px">
              <strong>Buyer:</strong>
              {{ $bundleCardGenerationDetail['buyer']? $bundleCardGenerationDetail['buyer']['name'] : '' }}
              <strong>{{ localizedFor('Style') }}:</strong> {{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}
              <strong>Color:</strong> {{ $sticker->first()['color'] }},
              <strong>Cutting No.</strong> {{ $sticker->first()['cutting_no'] }}
            </p>
          </div>
          <table class="table table-sm table-bordered">
            <thead>
              <tr>
                <th>Size & Suffix</th>
                <th>Serial Range</th>
              </tr>
            </thead>
            <tbody>
              @foreach($sticker as $sizeAndSuffix => $stickerDetails)
              <tr>
                <td>{{ $sizeAndSuffix }}</td>
                <td>{{ $stickerDetails['sl_start'] . ' - ' . $stickerDetails['sl_end'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endforeach
        @if($bundleCards && $bundleCards->count())
          <div class="col-md-8">
            <table class="table table-sm table-bordered">
              <thead>
                <tr>
                  <th>{{ localizedFor('PO') }}/Cntry</th>
                  <th>Size & Suffix</th>
                  <th>Bndl</th>
                  <th>Qty</th>
                  <th>Serial Range</th>
                </tr>
              </thead>
              <tbody>
                @foreach($bundleCards->groupBy('size_id') as $size_id => $bundleBySize)
                @foreach(collect($bundleBySize)->groupBy('suffix') as $suffix => $bundleBySizeSuffix)
                @foreach(collect($bundleBySizeSuffix)->groupBy('purchase_order_id') as $purchase_order_id =>
                $bundleByPO)
                @php
                $bundleByPO = collect($bundleByPO);
                $po_no = $bundleByPO->first()['purchase_order']['po_no'] ??
                $bundleByPO->first()['purchaseOrder']['po_no'] ?? '';
                $sizeAndSuffix = $bundleBySizeSuffix->first()['suffix'] ? $bundleBySize->first()['size']['name'] . '
                (' . $bundleBySizeSuffix->first()['suffix'] . ')' : $bundleBySize->first()['size']['name'];

                $sl_start = explode('-', str_replace('/','-', $bundleBySizeSuffix->first()['serial']))[0];
                $sl_end = array_last(explode('-', str_replace('/','-', $bundleBySizeSuffix->last()['serial'])));

                $bundles = $bundleByPO->count();
                $bundle_serials = $bundles > 1 ? $bundleByPO->first()['serial'].'<br>'.$bundleByPO->last()['serial']
                : $bundleByPO->first()['serial'];

                $qty = $bundleByPO->sum('quantity');
                @endphp
                <tr>
                  <td>{{ $po_no }}</td>
                  <td>{{ $sizeAndSuffix }}</td>
                  <td>{{ $bundles }}</td>
                  <td>{{ $qty }}</td>
                  <td>{!! $bundle_serials !!}</td>
                  {{--<td>{{ $sl_start . ' - ' . $sl_end }}</td>--}}
                </tr>
                @endforeach
                @endforeach
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
      </div>
      <hr class="noprint">
      <div class="newpage"></div>
      <br>
      <div class="bundle-cards">
        <?php
            $totalPage = ceil(count($bundleCards) / 14);
            ?>

        @for($page = 1; $page <= $totalPage; $page++) @for($i=0; $i < 7; $i++) <?php
                    $bundleCardFirstCol = $bundleCards->where('bundle_no', (($totalPage * $i) + $page))->first();
                    $bundleCardSecondCol = $bundleCards->where('bundle_no', (($totalPage * (7 + $i)) + $page))->first();
                    ?> <div class="row margin9">
          @if($bundleCardFirstCol && count($bundleCardFirstCol))
          @php
          $scanable_rp_barcode = $bundleCardFirstCol['id'] ? '1' . str_pad($bundleCardFirstCol['id'], 8, '0', STR_PAD_LEFT) : '';
          $scanable_op_barcode = $bundleCardFirstCol['id'] ? str_pad($bundleCardFirstCol['id'], 9, '0', STR_PAD_LEFT) : '';
          $bundeCardSizeId = $bundleCardFirstCol['size_id'] ?? null;
          $bundeCardSizeSuffix = $bundleCardFirstCol['suffix'] ?? null;
          $ratioQuery = collect($bundleCardGenerationDetail['ratios'])->where('size_id', $bundeCardSizeId)->where('suffix', $bundeCardSizeSuffix)->first();
          $bundleRatio = (count($ratioQuery) && $bundle_card_sticker_ratio_view_status) ? $ratioQuery['ratio'] : null;
          @endphp
          <div class="col-sm-6">
            <div class="row">
              <div class="col-sm-6">
                <table class="table table-sm table-bordered"
                  style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }}">
                  <tbody>
                    <tr>
                      <td colspan="2" align="center" class="double-col">
                        {{ $bundleCardGenerationDetail['buyer']['name'] ?? '' }}
                        {{ isset($bundleCardGenerationDetail['order']['reference_no']) ? '- '.$bundleCardGenerationDetail['order']['reference_no'] : '' }}
                        -{{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}
                        ,OQ: {{ $bundleCardGenerationDetail['order_quantity'] ?? '' }}</td>
                      <td class="third text-left">
                        &nbsp;{{ 'T:'.($bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ?? '') }}
                      </td>
                    </tr>
                    <tr>
                      <td class="first">{{ localizedFor('PO') }}</td>
                      <td class="second">
                        {{ $bundleCardFirstCol['purchase_order']['po_no'] ?? $bundleCardFirstCol['purchaseOrder']['po_no'] ?? '' }}
                        -({{ substr($bundleCardGenerationDetail['order']['style_name'], -10) }})
                      </td>
                      <td class="third text-left"></td>
                    </tr>
                    <tr>
                      <td class="first">Color</td>
                      <td class="second">{{ $bundleCardFirstCol['color']['name'] ?? '' }}</td>
                      <td class="third text-left"></td>
                    </tr>
                    <tr>
                      <td class="first">Lot</td>
                      <td class="second">{{ $lots->where('id', $bundleCardFirstCol['lot_id'])->first()->lot_no ?? '' }} </td>
                      <td class="third text-left"></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center" class="double-col">
                        RP: {{ $scanable_rp_barcode ?? '' }} |
                        OP: {{ $scanable_op_barcode ?? '' }}
                      </td>
                      <td class="third text-left"></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center" class='double-col barcode'>
                        <span><?php echo DNS1D::getBarcodeSVG(($scanable_rp_barcode ?? ''), "C128A", 1.2, 19, '', false); ?></span>
                      </td>
                      <td class="third text-left">
                        {{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 20) : '' }}
                        <br>{{ $bundleCardGenerationDetail['type'] ? substr($bundleCardGenerationDetail['type']['name'], 0, 9) : '' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-sm table-bordered"
                  style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }}">
                  <tr>
                    <td class="third text-right">
                      {{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 9) : '' }}
                    </td>
                    <td class="first">Bundle No.</td>
                    <td class="second">
                      {{ $bundleCardFirstCol[getbundleCardSerial()] ?? $bundleCardFirstCol['size_wise_bundle_no'] ?? $bundleCardFirstCol['bundle_no'] }}
                      (Roll
                      No. {{ $bundleCardFirstCol['roll_no'] ?? '--' }})
                    </td>
                  </tr>
                  <tr>
                    <td class="third text-right">
                      {{ $bundleCardGenerationDetail['factory']['factory_short_name'] ?? '' }}</td>
                    <td class="first">Size Name</td>
                    <td class="second">{{ ($bundleCardFirstCol['size']['name'] ?? '').($bundleCardFirstCol['suffix'] ? '('.$bundleCardFirstCol['suffix'].')' : '') }}</td>
                  </tr>
                  <tr>
                    <td class="third text-right"></td>
                    <td class="first">Quantity</td>
                    <td class="second">{{ $bundleCardFirstCol['quantity'] }} {{ $bundleRatio ? '- ('.$bundleRatio.')' : ''}}</td>
                  </tr>
                  <tr>
                    <td class="third text-right"></td>
                    <td class="first">Serial No</td>
                    <td class="second" style="{{ $bundleCardFirstCol['sl_overflow'] ? 'color: red' : '' }}">
                      {{ $bundleCardFirstCol['serial'] }}
                    </td>
                  </tr>
                  <tr>
                    <td class="third text-right"></td>
                    <td class="first">Cutt. No</td>
                    <td class="second">{{ $bundleCardFirstCol['cutting_no'] }} {{ isset($garmentsItem) ? '('.$garmentsItem.')' : '' }}</td>
                  </tr>
                  <tr>
                    <td class="third text-right">
                      {{ $bundleCardGenerationDetail['created_at'] ? date('Y-m-d', strtotime($bundleCardGenerationDetail['created_at'])) : '' }}
                    </td>
                    <td colspan="2" align="center" class="double-col barcode">
                      <span><?php echo DNS1D::getBarcodeSVG(($scanable_op_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?></span>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          @endif
          @if($bundleCardSecondCol && count($bundleCardSecondCol))
          @php
          $scanable_rp_barcode = $bundleCardSecondCol['id'] ? '1' . str_pad($bundleCardSecondCol['id'], 8, '0', STR_PAD_LEFT) : '';
          $scanable_op_barcode = $bundleCardSecondCol['id'] ? str_pad($bundleCardSecondCol['id'], 9, '0', STR_PAD_LEFT) : '';
          $bundeCardSizeId = $bundleCardSecondCol['size_id'] ?? null;
          $bundeCardSizeSuffix = $bundleCardSecondCol['suffix'] ?? null;
          $ratioQuery = collect($bundleCardGenerationDetail['ratios'])->where('size_id', $bundeCardSizeId)->where('suffix', $bundeCardSizeSuffix)->first();
          $bundleRatio = (count($ratioQuery) && $bundle_card_sticker_ratio_view_status) ? $ratioQuery['ratio'] : null;
          @endphp
          <div class="col-sm-6">
            <div class="row">
              <div class="col-sm-6">
                <table class="table table-sm table-borderd"
                  style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }}">
                  <tr>
                    <td colspan="2" align="center" class="double-col">
                      {{ $bundleCardGenerationDetail['buyer']['name'] ?? '' }}
                      {{ isset($bundleCardGenerationDetail['order']['reference_no']) ? '- '.$bundleCardGenerationDetail['order']['reference_no'] : '' }}
                      -{{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}
                      ,OQ: {{ $bundleCardGenerationDetail['order_quantity'] ?? '' }}</td>
                    <td class="third text-left">
                      {{ 'T:'.($bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ?? '') }}
                    </td>
                  </tr>
                  <tr>
                    <td class="first">{{ localizedFor('PO') }}</td>
                    <td class="second">
                      {{ $bundleCardSecondCol['purchase_order']['po_no'] ?? $bundleCardSecondCol['purchaseOrder']['po_no'] ?? '' }}
                      -({{ substr($bundleCardGenerationDetail['order']['style_name'], -10) }})
                    </td>
                    <td class="third text-left"></td>
                  </tr>
                  <tr>
                    <td class="first">Color</td>
                    <td class="second">{{ $bundleCardSecondCol['color']['name'] ?? '' }}</td>
                    <td class="third text-left"></td>
                  </tr>
                  <tr>
                    <td class="first">Lot</td>
                    <td class="second">{{ $lots->where('id', $bundleCardSecondCol['lot_id'])->first()->lot_no ?? '' }}</td>
                    <td class="third text-left"></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center" class="double-col">
                      RP: {{ $scanable_rp_barcode ?? '' }} |
                      OP: {{ $scanable_op_barcode ?? '' }}
                    </td>
                    <td class="third text-left"></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center" class='double-col barcode'>
                      <span><?php echo DNS1D::getBarcodeSVG(($scanable_rp_barcode ?? ''), "C128A", 1.2, 19, '', false); ?></span>
                    </td>
                    <td class="third text-left">
                      {{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 20) : '' }}
                      <br>{{ $bundleCardGenerationDetail['type'] ? substr($bundleCardGenerationDetail['type']['name'], 0, 9) : '' }}
                    </td>
                  </tr>
                </table>
              </div>
              <div class="col-sm-6">
                <table class="table table-sm table-borderd"
                  style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }}">
                  <tr>
                    <td class="third text-right">
                      {{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 9) : '' }}
                    </td>
                    <td class="first">Bundle No.</td>
                    <td class="second">
                      {{ $bundleCardSecondCol[getbundleCardSerial()] ?? $bundleCardSecondCol['size_wise_bundle_no'] ?? $bundleCardSecondCol['bundle_no'] }}
                      (Roll No. {{ $bundleCardSecondCol['roll_no'] ?? '--' }})
                    </td>
                  </tr>
                  <tr>
                    <td class="third text-right">
                      {{ $bundleCardGenerationDetail['factory']['factory_short_name'] ?? '' }}</td>
                    <td class="first">Size Name</td>
                    <td class="second">{{ ($bundleCardSecondCol['size']['name'] ?? '').($bundleCardSecondCol['suffix'] ? '('.$bundleCardSecondCol['suffix'].')' : '') }}</td>
                  </tr>
                  <tr>
                    <td class="third text-right"></td>
                    <td class="first">Quantity</td>
                    <td class="second">{{ $bundleCardSecondCol['quantity'] }} {{ $bundleRatio ? '- ('.$bundleRatio.')' : ''}}</td>
                  </tr>
                  <tr>
                    <td class="third text-right"></td>
                    <td class="first">Serial No</td>
                    <td class="second" style="{{ $bundleCardSecondCol['sl_overflow'] ? 'color: red' : '' }}">
                      {{ $bundleCardSecondCol['serial'] }}
                    </td>
                  </tr>
                  <tr>
                    <td class="third text-right"></td>
                    <td class="first">Cutt. No</td>
                    <td class="second">{{ $bundleCardSecondCol['cutting_no'] }} {{ isset($garmentsItem) ? '('.$garmentsItem.')' : '' }}</td>
                  </tr>
                  <tr>
                    <td class="third text-right">
                      {{ $bundleCardGenerationDetail['created_at'] ? date('Y-m-d', strtotime($bundleCardGenerationDetail['created_at'])) : '' }}
                    </td>
                    <td colspan="2" align="center" class="double-col barcode">
                      <span><?php echo DNS1D::getBarcodeSVG(($scanable_op_barcode ?? ''), "C128A", 1.2, 19, '', false); ?></span>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          @endif
      </div>
      @endfor
      @if( $page != $totalPage)
      <div class="newpage"></div>
      <br>
      @endif
      @endfor
    </div>
  </div>
</div>
<h1 class="print-permit-message"
  style="display: none; text-align: center; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;">
  Please click the print button to print Bundle Card <br>
  Thanks!
</h1>
</div>
<iframe id="printable" name="printf"
  src="{{ url('bundle-card-generations/'.$bundleCardGenerationDetail['id'].'/print') }}" width="100%"
  style="visibility: hidden;"></iframe>
@endsection

@section('scripts')
<script type="text/javascript">
  $(() => {
    $('.AllContent').removeClass('hide');
  });

  $('body').on('click', '.print-btn', function (event) {
      // var newWin = window.open();

      $("#printable").get(0).contentWindow.print();

      /* Get the window's document and write the html content. */
      // newWin.document.write(html);
      // newWin.print();
      // newWin.close();
      // setTimeout(function(){newWin.close();},10);
    });

    // For browser print restriction
    if ('matchMedia' in window) {
      // Chrome, Firefox, and IE 10 support mediaMatch listeners
      window.matchMedia('print').addListener(function (media) {
        if (media.matches) {
          beforePrint();
        } else {
          // Fires immediately, so wait for the first mouse movement
          $(document).one('mouseover', afterPrint);
        }
      });
    } else {
      // IE and Firefox fire before/after events
      $(window).on('beforeprint', beforePrint);
      $(window).on('afterprint', afterPrint);
    }

    function beforePrint() {
      $(".AllContent").hide();
      $(".print-permit-message").show();
    }

    function afterPrint() {
      $(".AllContent").show();
      $(".print-permit-message").hide();
    }
</script>
@endsection
