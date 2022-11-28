<div>
    <div>
        <table class="borderless">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span><br>
                    <b>{{ factoryAddress() }}</b><br>
                    <span>Tel: +8809610-864328, Mail: info@gears-group.com</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
        <hr>
    </div>
    <center>
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Trims Bookings</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>

    <div class="body-section" style="margin-top: 0px;">
        <table class="borderless">
            <tr>
                <th colspan="2" style="text-align: center"><b><u>PURCHASE ORDER</u></b></th>
            </tr>
            <tr>
                <th style="width: 250px">SUPPLIER:</th>
                <td>{{ isset($trimsBookings) ? optional($trimsBookings->supplier)->name : '' }}</td>
            </tr>
            <tr>
                <th style="width: 250px">BUYER:</th>
                <td>{{ isset($trimsBookings) ? optional($trimsBookings->buyer)->name : ''}}</td>
            </tr>
            <tr>
                <th style="width: 250px">ATTN:</th>
                <td>{{ $trimsBookings->attention ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 250px">ORDER DATE:</th>
                <td>{{  $trimsBookings->booking_date ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 250px">REVISION NO:</th>
                <td></td>
            </tr>
            <tr>
                <th style="width: 250px">ISSUED BY:</th>
                <td>{{ $trimsBookings->issued_by ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 250px">APPROVED BY:</th>
                <td></td>
            </tr>
        </table>


        @if(isset($trimsBookings) && count((optional($trimsBookings)->trimsDetails)) > 0)
            <div style="margin-top: 15px;">
                <table>
                    <tr>
                        <td colspan="13" class="text-center"><b>TRIMS DETAILS</b></td>
                    </tr>
                    <tr>
                        <th>SL</th>
                        <th>STYLE</th>
                        <th>PO</th>
                        <th>GMT COLOR</th>
                        <th>GMT SIZE</th>
                        <th>GMT QTY</th>
                        <th>UNIT</th>
                        <th>ITEM DESCRIPTION</th>
                        <th>ITEM COLOR</th>
                        <th>ITEM SIZE</th>
                        <th>UOM</th>
                        <th>BOOKING QTY</th>
                        <th>REMARKS</th>
                    </tr>

                    @foreach($trimsBookings->trimsDetails as $key => $item)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $item['style_name'] }}</td>
                            <td>{{ $item['po'] }}</td>
                            <td>{{ $item['gmt_color'] }}</td>
                            <td>{{ $item['gmt_size'] }}</td>
                            <td>{{ $item['gmt_qty'] }}</td>
                            <td>{{ $item['unit'] }}</td>
                            <td>{{ $item['item_description'] }}</td>
                            <td>{{ $item['item_color'] }}</td>
                            <td>{{ $item['item_size'] }}</td>
                            <td>{{ $item['uom'] }}</td>
                            <td>{{ $item['booking_qty'] }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

    </div>

    <div>

    </div>

    <div style="margin-top: 16mm">
        <table class="borderless">
            <tbody>
            <tr>
                <th class="text-left"><b><u>Terms and Conditions:</u></b></th>
            </tr>
            @if(isset($trimsBookings))
                @foreach(collect($trimsBookings->terms_condition)->flatten() as $item)
                    <tr>
                        <td>{{ '* ' .  $item }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
