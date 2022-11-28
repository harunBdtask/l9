<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Department List</title>
    @include('merchandising::download.include.report-style')
</head>
<body>
@include('merchandising::download.include.report-header')
@include('merchandising::download.include.report-footer')
<main>
    <h4 align="center" style="margin-top: -5px">Product Department List</h4>
    <table class="reportTable">
        <thead>
        <tr>
            <th width="20%">SL</th>
            <th width="30%">Product Department</th>
            <th width="30%">Company</th>
            <th width="30%">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($product_departments as $product_department)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product_department->product_department }}</td>
                <td style="background: #0F733B;color: #fff;font-weight: bold;letter-spacing: 1px">{{$product_department->factory->factory_name}}</td>
                <td>{{ $product_department->status == 1? "Active" : ($product_department->status == 2 ? "In Active": "Cancelled") }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>

</body>

</html>
