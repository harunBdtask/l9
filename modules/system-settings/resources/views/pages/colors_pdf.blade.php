<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Color List</title>
    @include('merchandising::download.include.report-style')
</head>
<body>
@include('merchandising::download.include.report-header')
@include('merchandising::download.include.report-footer')
<main>
    <h4 align="center" style="margin-top: -5px">Color List</h4>
    <table class="reportTable">
        <thead>
        <tr>
            <th>SL</th>
            <th>Color Name</th>
            <th>Company</th>
        </tr>
        </thead>
        <tbody>
        @foreach($colors as $color)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $color->name }}</td>
                <td style="background: #0F733B;color: #fff;font-weight: bold;letter-spacing: 1px">{{$color->factory->factory_name}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
</main>

</body>

</html>
