<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode</title>
    <style>
        .item{
            margin: 7px;
            display: inline-block;
            border: 1px solid #000;
            padding: 6px;
        }
        .item p{
            text-align: center;
            font-weight: bold;
            margin-block-start: 0px;
            margin-block-end: 0px;
            font-size: 18px;
        }
        @media print {
            #goBack {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-md-12">
        @forelse($machines as $machine)
            <div class="item">
                @php echo DNS1D::getBarcodeSVG($machine->barcode, 'C39', 1.5,44, 'black', false); @endphp
                <p>{{ $machine->barcode }} </p>
            </div>

        @empty
        @endforelse
        <div class="text-center">
        <a id="goBack" href="{{ url('mc-inventory/machine-barcode-generation/create?id='.$id) }}">Go Back</a>
        </div>
        
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>
