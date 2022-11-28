<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>goRMG | An Ultimate ERP Solutions For Garments</title>
    <meta name="description" content="RMG, ERP, Production Tracking"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('refresh')

    <link rel="shortcut icon" sizes="196x196"
          href="{{ asset('modules/skeleton/flatkit/assets/images/gormg_fav_ico.png') }}">

    <!-- style -->
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet"
          href="{{ asset('modules/skeleton/flatkit/assets/material-design-icons/material-design-icons.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css"/>

    <!-- libs -->
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/datepicker/datepicker3.css')}}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/select2/select2.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/morris-charts/morris.css')}}">
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/toaster/toaster.css')}}">
    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>
</head>
<body>
<div class="box">
    <div class="box-header">
        <h4 class="text-center">{{ get_company_details()->name ?? 'PN COMPOSITE LTD' }}</h4>
        <h4 class="text-center">{{ get_company_details()->address ?? '' }}</h4>

        <div class="pull-right print_button" style="margin-top: -3%;" onclick="printDiv('box-body')">
            <a class="btn print">
                <i class="fa fa-print"></i>
            </a>
        </div>

    </div>
    <div class="box-body" id="box-body">
        @if(isset($dyes_barcode))
            @foreach($dyes_barcode->chunk(3) as $chunk)
                <div class="row" id="barcode-row">
                    @foreach($chunk as $barcode)
                        <div class="col-sm-4">
                            <table class="reportTable">
                                <tr class="text-left">
                                    <td class="p-l">Item</td>
                                    <td class="p-l">{{ $barcode->item->name ?? 'N/A' }}</td>
                                </tr>
                                <tr class="text-left">
                                    <td class="p-l">Brand</td>
                                    <td class="p-l">{{ $barcode->brand->name ?? 'N/A' }}</td>
                                </tr>
                                <tr class="text-left">
                                    <td class="p-l">Qty</td>
                                    <td class="p-l">{{ $barcode->qty ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-top: 5px;">
                                        <span><?php echo DNS1D::getBarcodeSVG(($barcode->code ?? '1234'), "C128A", 1.2, 35); ?></span><br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

</div>
</body>
<script type="text/javascript">
    function printDiv(divName) {
        let printContents = document.getElementById(divName).innerHTML;
        let originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
</html>
