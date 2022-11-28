<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Short Trim Book Report</title>
    @include('merchandising::download.include.report-style')
</head>

<body>
@include('merchandising::download.include.report-header')
@include('merchandising::download.include.report-footer')

<main>
    <div class="padding">
        <div class="box">
            <div class="box-body b-t ">
                <div class="table-responsive">
                    <table class="reportTable">
                        <thead>
                        <tr>
                            <th>Booking No</th>
                            <th>Trim Short Booking</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trimBooking as $key => $shortBook)
                            <tr>
                                <td> {{ $key  }}</td>
                                <td>{{ $shortBook }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{--<div class="text-center print-delete"> {{$recap->render()}}</div>--}}
                    {{--<div class="text-center print-delete">{{$recap->appends($_GET)->links() }}</div>--}}
                </div>
            </div>
        </div>
    </div>
</main>
</body>

</html>