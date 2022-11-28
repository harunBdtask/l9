<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order List</title>
    @include('merchandising::download.include.report-style')
</head>
<body>
@include('merchandising::download.include.report-header')
@include('merchandising::download.include.report-footer')
<main>
    <h4 align="center" style="margin-top: -5px">Composition List</h4>
    <table class="reportTable">
        <thead>
        <tr>
            <th>Composition Name</th>
            <th>Company</th>
        </tr>
        </thead>
        <tbody>
        @foreach($fabric_compositions as $fabric_composition)
            <tr>
                <td>{{$fabric_composition->yarn_composition}}</td>
                <td style="background: #0F733B;color: #fff;font-weight: bold;letter-spacing: 1px">{{$fabric_composition->factory->factory_name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>

</body>

</html>
