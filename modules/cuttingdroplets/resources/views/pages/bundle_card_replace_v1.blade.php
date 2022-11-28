<div class="row noprint">
  <div class="col-md-12">
    <p class="font-italic">{!! $bundle->buyer ? "<strong>Buyer: </strong>".$bundle->buyer->name : '' !!} 
    {!!$bundle->order ? "<strong>Style: </strong>".$bundle->order->style_name: '' !!} 
    {!! ($bundle->order && $bundle->order->reference_no) ? "<strong>Ref. No.: </strong>".$bundle->order->reference_no: '' !!}
    </p>
  </div>
</div>
<div class="row">
  <div class="col-sm-7">
    <div class="row bundle-cards">
      <div class="col-sm-6">
        <div class="row">
          <table class="reportTable"
            style="{{ $bundle ? '' : 'visibility: hidden;' }};width: 300px !important">
            <tbody>
              <tr>
                <td colspan="2" align="center" class="double-col">{{ $bundle->buyer->name ?? '' }}
                  - {{ $bundle->order->style_name ?? '' }},
                  OQ: {{ $bundle->order->pq_qty_sum ?? '' }}</td>
                <td class="third text-left">
                  &nbsp;{{ 'T:'.$bundle->cuttingTable->table_no ?? '' }}</td>
              </tr>
              <tr>
                <td class="first">PO</td>
                <td class="second">{{ $bundle->purchaseOrder->po_no ?? '' }}</td>
                <td class="third text-left"></td>
              </tr>
              <tr>
                <td class="first">Color</td>
                <td class="second">{{ $bundle->color->name ?? '' }}</td>
                <td class="third text-left"></td>
              </tr>
              <tr>
                <td class="first">Lot</td>
                <td class="second">{{ $bundle->lot->lot_no ?? '' }} </td>
                <td class="third text-left"></td>
              </tr>
              <tr>
                <td colspan="2" align="center" class="double-col">
                  RP: {{ $bundle->scanable_rp_barcode ?? '' }} |
                  OP: {{ $bundle->scanable_op_barcode ?? '' }}
                </td>
                <td class="third text-left"></td>
              </tr>
              <tr>
                <td colspan="2" align="center" class='double-col barcode'>
                  <span>
                    <?php echo DNS1D::getBarcodeSVG(($bundle->scanable_rp_barcode ?? ''), "C128A", 1.2, 19, '', false); ?>
                  </span>
                </td>
                <td class="third text-left">{{ $bundle->part ? substr($bundle->part->name, 0, 20) : '' }}
                  <br>{{ $bundle->type ? substr($bundle->type->name, 0, 9) : '' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-6">
        <table class="reportTable" style="{{ $bundle ? '' : 'visibility: hidden;' }};width: 300px !important">
          <tr>
            <td class="third text-right">{{ $bundle->part ? substr($bundle->part->name, 0, 9) : '' }}</td>
            <td class="first">Bundle No.</td>
            <td class="second">
              {{ $bundle[getbundleCardSerial()] ?? $bundle['bundle_no'] ?? $bundle['size_wise_bundle_no'] }}
            </td>
          </tr>
          <tr>
            <td class="third text-right">{{ $bundleCardGenerationDetail->factory->factory_short_name ?? '' }}
            </td>
            <td class="first">Size Name</td>
            <td class="second">{{ $bundle->size_name ?? '' }}</td>
          </tr>
          <tr>
            <td class="third text-right"></td>
            <td class="first">Quantity</td>
            <td class="second">{{ $bundle['quantity'] }}</td>
          </tr>
          <tr>
            <td class="third text-right"></td>
            <td class="first">Serial No</td>
            <td class="second" style="{{ $bundle['sl_overflow'] ? 'color: red' : '' }}">
              {{ $bundle['serial'] }}
            </td>
          </tr>
          <tr>
            <td class="third text-right"></td>
            <td class="first">Cutt. No</td>
            <td class="second">{{ $bundle['cutting_no'] }}</td>
          </tr>
          <tr>
            <td class="third text-right">{{ $bundle->created_at->format('Y-m-d') ?? '' }}</td>
            <td colspan="2" align="center" class="double-col barcode">
              <span>
                <?php echo DNS1D::getBarcodeSVG(($bundle->scanable_op_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?>
              </span>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>