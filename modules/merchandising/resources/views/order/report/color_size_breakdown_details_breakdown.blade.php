<table class="reportTable">
    <thead>
    <tr class="title-row">
        <th colspan="12"><b>COLOR BREAKDOWN</b></th>
    </tr>
    <tr>
        <th><b>BUYER</b></th>
        <th><b>STYLE</b></th>
        <th><b>PORT</b></th>
        <th><b>Comm. File No</b></th>
        <th><b>ITEM</b></th>
        <th><b>PO</b></th>
        <th><b>COLOR</b></th>
        <th><b>QUANTITY / PACK</b></th>
        <th><b>QUANTITY / PCS</b></th>
        <th><b>FOB</b></th>
        <th><b>TOTAL <br> VALUE</b></th>
        <th><b>ACTUAL SHIP DATE</b></th>
    </tr>
    </thead>
    <tbody>

    <?php
    $fobTotal = 0;
    $qtyPerPackTotal = 0;
    $grandTotal = 0;
    ?>


    @forelse(collect($pos)->groupBy('buyer') as $buyer => $buyerWise)
        @foreach(collect($buyerWise)->groupBy('style') as $style => $styleWise)
            @foreach(collect($styleWise)->groupBy('item') as $item => $itemWise)
                @foreach(collect($itemWise)->groupBy('po') as $po => $poWise)
                    @foreach($poWise as $itemWise)
                        @if($loop->iteration == 1)
                            <tr>
                                <td rowspan="{{ count($poWise) }}">{!!  isset($itemWise['buyer']) ? str_replace("&","&#38;",$itemWise['buyer']) : '' !!} </td>
                                <td rowspan="{{ count($poWise) }}">{!! isset($itemWise['style']) ? str_replace("&","&#38;",$itemWise['style']) : '' !!}</td>
                                <td rowspan="{{ count($poWise) }}">{{$itemWise['port']}}</td>
                                <td rowspan="{{ count($poWise) }}">{{$itemWise['comm_file_no']}}</td>
                                <td rowspan="{{ count($poWise) }}">{{$itemWise['item']}}</td>
                                <td rowspan="{{ count($poWise) }}">{!! isset($itemWise['po']) ? str_replace("&","&#38;",$itemWise['po']) : '' !!}</td>
                                <td>{{$itemWise['color']}}</td>
                                <?php
                                $fobTotal += $itemWise['fob'];
                                $qtyPerPackTotal += $itemWise['quantity_per_pack'];
                                $grandTotal += $itemWise['total_value'];
                                ?>
                                <td class="text-right"
                                    rowspan="{{ count($poWise) }}">{{$itemWise['quantity_per_pack']}}</td>
                                <td class="text-right">{{$itemWise['quantity_per_pcs']}}</td>
                                <td class="text-right" rowspan="{{ count($poWise) }}">{{$itemWise['fob']}}</td>
                                <td class="text-right" rowspan="{{ count($poWise) }}">{{$itemWise['total_value']}}</td>
                                <td class="text-right" style="width: 80px"
                                    rowspan="{{ count($poWise) }}">{{$itemWise['shipment_date']}}</td>
                            </tr>
                        @else
                            <tr>
                                {{--                               <td>{{$itemWise['item']}}</td>--}}
                                <td>{{$itemWise['color']}}</td>
                                <td class="text-right">{{$itemWise['quantity_per_pcs']}}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    @empty
        <td colspan="12">No Data</td>
    @endforelse
    <tr>
        <td colspan="7" class="text-right"><b>Total</b></td>
        <td class="text-right"><b>{{ $qtyPerPackTotal }}</b></td>
        <td colspan="2"></td>
        {{--        <td class="text-right"><b>${{ $fobTotal }}</b></td>--}}
        <td class="text-right"><b>${{ $grandTotal}}</b></td>
        <td></td>
    </tr>
    </tbody>
</table>
