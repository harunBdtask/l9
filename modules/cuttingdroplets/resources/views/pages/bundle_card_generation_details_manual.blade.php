@extends('cuttingdroplets::layout')

@section('title', 'Bundle Card Manual')
@section('styles')
  <style type="text/css">
    .bundle-cards table, .bundle-cards th, .bundle-cards td {
      border: 1px solid black !important;
    }

    .bundle-card-generation-details table, .bundle-card-generation-details th, .bundle-card-generation-details td {
      border: 1px solid black !important;
    }

    .bundle-cards td.third {
      display: none;
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

@inject('lot', 'SkylarkSoft\GoRMG\SystemSettings\Models\Lot')
@php
  error_reporting(0);
  $garmentsItem = $bundleCardGenerationDetail['garments_item']['name'] ?? $bundleCardGenerationDetail['garmentsItem']['name'] ?? '';
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
            <small class="text-center">No consumption information for manual bundle card generation</small>
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
          <div class="col-sm-4 pull-left">
            <h2>Session No. : {{ str_pad($bundleCardGenerationDetail['sid'], 4, "0", STR_PAD_LEFT) }}</h2>
            {!! $bundleCardGenerationDetail['created_at'] ? '<br><h2>Date: '.Date('d/m/Y', strtotime($bundleCardGenerationDetail['created_at'])).'</h2>' : '' !!}
          </div>
          <div class="col-sm-4 pull-right">
            <div class="pull-right">
              @if($bundleCards->count() > 0)
              <a href="{{ url('bundle-card-generation-manual/'.$bundleCardGenerationDetail['id'].'/re-generate') }}"
                 class="btn btn-sm white m-b"><i class="fa fa-retweet"></i> Regenerate</a>
              @endif
              <button class="btn btn-sm white m-b print-btn" type="button">
                <i class="fa fa-print"></i> Print
              </button>
            </div>
            {!! Form::open(['url' => 'bundle-card-generation-manual/'.$bundleCardGenerationDetail['id'].'/scan', 'method' => 'POST', 'onsubmit' => "this.style.visibility = 'hidden'"]) !!}
            @if($bundleCards->count() > 0 && $bundleCards->sum('status') == 0)
              <button class="btn btn-sm white m-b pull-right" type="submit" style="margin-right: 5px;">
                <i class="fa fa-bolt"></i> Scan Now
              </button>
            @endif
            {!! Form::close() !!}
          </div>
        </div>
      </div>
      <div class="box-body b-t bundle-card-generation-details">
        <div class="row">
          @foreach($stickers as $sticker)
            <div class="col-md-4">
              <div>
                <p class="text-center" style="margin-bottom: 5px">
                  <strong>Buyer:</strong> {{ $bundleCardGenerationDetail['buyer'] ? $bundleCardGenerationDetail['buyer']['name'] : '' }}
                  <strong>{{ localizedFor('Style') }}:</strong> {{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}
                  @if(isset($bundleCardGenerationDetail['order']['reference_no']))
                    <strong>Ref. No:</strong> {{ $bundleCardGenerationDetail['order']['reference_no'] ?? '' }}
                  @endif
                  <strong>Color:</strong> {{ $sticker && is_array($sticker) ? collect($sticker)->first()['color'] : $sticker->first()['color'] }},
                  <strong>Cutting Table.</strong> {{ $bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ?? '' }},
                  <strong>Item</strong> {{ $garmentsItem }}
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
              <table class="table table-sm table-bordered">
                <thead>
                <tr>
                  <th>Sl</th>
                  <th>Size</th>
                  <th>Bundles</th>
                  <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                @php
                  $sizeSl = 0;
                  $sizeSl = 0;
                  $bundles=0;
                  $quantity=0;
                @endphp
                @foreach($sticker as $sizeAndSuffix => $stickerDetails)
                  <tr>
                    <td>{{ ++$sizeSl }}</td>
                    <td>{{ $sizeAndSuffix }}</td>
                    <td>{{ $stickerDetails['bundles'] }}</td>
                    <td>{{ $stickerDetails['quantity'] }}</td>
                  </tr>
                  @php
                    $bundles += $stickerDetails['bundles'];
                    $quantity += $stickerDetails['quantity'];
                  @endphp
                @endforeach
                <tr>
                  <th colspan="2">Total</th>
                  <th>{{ $bundles }}</th>
                  <th>{{ $quantity }}</th>
                </tr>
                </tbody>
              </table>
            </div>
          @endforeach
        </div>
        <hr class="noprint">
        <div class="newpage"></div>
        <br>

        <div class="bundle-cards">
            <?php
            $totalPage = ceil(count($bundleCards) / 14);
            $starter = $bundleCards->first()['bundle_no'] ?? 1;
            $starter -= 1;
            ?>

          @for($page = 1; $page <= $totalPage; $page++)
            @for($i = 0; $i < 7; $i++)
                  <?php
                    $bundleCardFirstCol = $bundleCards->where('bundle_no', (($totalPage * $i) + $page + $starter))->first();
                    $bundleCardSecondCol = $bundleCards->where('bundle_no', (($totalPage * (7 + $i)) + $page + $starter))->first();
                  ?>
              <div class="row margin9">
                @if($bundleCardFirstCol && count($bundleCardFirstCol))
                  @php
                    $scanable_rp_barcode = $bundleCardFirstCol['id'] ? '1' . str_pad($bundleCardFirstCol['id'], 8, '0', STR_PAD_LEFT) : '';
                    $scanable_op_barcode = $bundleCardFirstCol['id'] ? str_pad($bundleCardFirstCol['id'], 9, '0', STR_PAD_LEFT) : '';
                  @endphp
                  <div class="col-sm-6">
                    <div class="row">
                      <div class="col-sm-6">
                        <table class="table table-sm table-bordered"
                               style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }}">
                          <tbody>
                          <tr>
                            <td colspan="2" align="center"
                                class="double-col">{{ $bundleCardGenerationDetail['buyer']['name'] ?? '' }}
                                {{ isset($bundleCardGenerationDetail['order']['reference_no']) ? '- '.$bundleCardGenerationDetail['order']['reference_no'] : '' }}
                              -{{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}
                              ,OQ: {{ $bundleCardGenerationDetail['order_quantity'] ?? '' }}</td>
                            <td class="third text-left">
                              &nbsp;{{ 'T:'.($bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no']) }}</td>
                          </tr>
                          <tr>
                            <td class="first">{{ localizedFor('PO') }}</td>
                            <td class="second">{{ $bundleCardFirstCol['purchase_order']['po_no'] ?? $bundleCardFirstCol['purchaseOrder']['po_no'] ?? '' }}
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
                            <td class="second">{{ $bundleCardFirstCol['lot']['lot_no'] ?? '' }} </td>
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
                              <span><?php echo DNS1D::getBarcodeSVG(($scanable_rp_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?></span>
                            </td>
                            <td class="third text-left">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 20) : '' }}
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
                            <td class="third text-right">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 9) : '' }}</td>
                            <td class="first">Bundle No.</td>
                            <td class="second">
                              {{ isset($bundleCardFirstCol['size_wise_bundle_no']) ? $bundleCardFirstCol['size_wise_bundle_no'] : $bundleCardFirstCol['bundle_no'] }} (Roll
                              No. {{ $bundleCardFirstCol['roll_no'] ?? '--' }})
                            </td>
                          </tr>
                          <tr>
                            <td class="third text-right">{{ $bundleCardGenerationDetail['factory']['factory_short_name'] ?? '' }}</td>
                            <td class="first">Size Name</td>
                            <td class="second">{{ ($bundleCardFirstCol['size']['name'] ?? '').($bundleCardFirstCol['suffix'] ? '('.$bundleCardFirstCol['suffix'].')' : '') }}</td>
                          </tr>
                          <tr>
                            <td class="third text-right"></td>
                            <td class="first">Quantity</td>
                            <td class="second">{{ $bundleCardFirstCol['quantity'] ?? '' }}</td>
                          </tr>
                          <tr>
                            <td class="third text-right"></td>
                            <td class="first">Serial No</td>
                            <td class="second">{{ $bundleCardFirstCol['serial'] ?? '' }}</td>
                          </tr>
                          <tr>
                            <td class="third text-right"></td>
                            <td class="first">Cutt. No</td>
                            <td class="second">{{ $bundleCardFirstCol['cutting_no'] ?? '' }} {{ $garmentsItem ? '('.$garmentsItem.')' : '' }}</td>
                          </tr>
                          <tr>
                            <td class="third text-right">{{ $bundleCardGenerationDetail['created_at'] ? date('Y-m-d', strtotime($bundleCardGenerationDetail['created_at'])) : '' }}</td>
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
                @endphp
                  <div class="col-sm-6">
                    <div class="row">
                      <div class="col-sm-6">
                        <table class="table table-sm table-borderd"
                               style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }}">
                          <tr>
                            <td colspan="2" align="center"
                                class="double-col">{{ $bundleCardGenerationDetail['buyer']['name'] ?? '' }}
                                {{ isset($bundleCardGenerationDetail['order']['reference_no']) ? '- '.$bundleCardGenerationDetail['order']['reference_no'] : '' }}
                              -{{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}
                              ,OQ: {{ $bundleCardGenerationDetail['order_quantity'] ?? '' }}</td>
                            <td class="third text-left">{{ 'T:'.($bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no']) }}</td>
                          </tr>
                          <tr>
                            <td class="first">{{ localizedFor('PO') }}</td>
                            <td class="second">{{ $bundleCardSecondCol['purchase_order']['po_no'] ?? $bundleCardSecondCol['purchaseOrder']['po_no'] ?? '' }}
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
                            <td class="second">{{ $bundleCardSecondCol['lot']['lot_no'] ?? '' }} </td>
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
                              <span><?php echo DNS1D::getBarcodeSVG(($scanable_rp_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?></span>
                            </td>
                            <td class="third text-left">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 20) : '' }}
                              <br>{{ $bundleCardGenerationDetail['type'] ? substr($bundleCardGenerationDetail['type']['name'], 0, 9) : '' }}
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div class="col-sm-6">
                        <table class="table table-sm table-borderd"
                               style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }}">
                          <tr>
                            <td class="third text-right">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 9) : '' }}</td>
                            <td class="first">Bundle No.</td>
                            <td class="second">
                              {{ isset($bundleCardSecondCol['size_wise_bundle_no']) ? $bundleCardSecondCol['size_wise_bundle_no'] : $bundleCardSecondCol['bundle_no'] }} (Roll
                              No. {{ $bundleCardSecondCol['roll_no'] ?? '--' }})
                            </td>
                          </tr>
                          <tr>
                            <td class="third text-right">{{ $bundleCardGenerationDetail['factory']['factory_short_name'] ?? '' }}</td>
                            <td class="first">Size Name</td>
                            <td class="second">{{ ($bundleCardSecondCol['size']['name'] ?? '').($bundleCardSecondCol['suffix'] ? '('.$bundleCardSecondCol['suffix'].')' : '') }}</td>
                          </tr>
                          <tr>
                            <td class="third text-right"></td>
                            <td class="first">Quantity</td>
                            <td class="second">{{ $bundleCardSecondCol['quantity'] ?? '' }}</td>
                          </tr>
                          <tr>
                            <td class="third text-right"></td>
                            <td class="first">Serial No</td>
                            <td class="second">{{ $bundleCardSecondCol['serial'] ?? '' }}</td>
                          </tr>
                          <tr>
                            <td class="third text-right"></td>
                            <td class="first">Cutt. No</td>
                            <td class="second">{{ $bundleCardSecondCol['cutting_no'] }} {{ $garmentsItem ? '('.$garmentsItem.')' : '' }}</td>
                          </tr>
                          <tr>
                            <td class="third text-right">{{ $bundleCardGenerationDetail['created_at'] ? date('Y-m-d', strtotime($bundleCardGenerationDetail['created_at'])) : '' }}</td>
                            <td colspan="2" align="center" class="double-col barcode">
                              <span><?php echo DNS1D::getBarcodeSVG(($scanable_op_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?></span>
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
          src="{{ url('bundle-card-generation-manual/'.$bundleCardGenerationDetail['id'].'/print') }}" width="100%"
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
