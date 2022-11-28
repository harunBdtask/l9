<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @include('inventory::report.report_style')

    <style>
        .bg-danger {
            color: #fff !important;
            background-color: #ea070087 !important;
        }

        .bg-gwhite {
            background: ghostwhite;
        }
    </style>
</head>
<body>
<h3 style="text-align: center; margin-top: -5px;">Items List</h3>

<main>
    <table class="reportTable">
        <thead>
        <tr>
            <th>Sl</th>
            <th>Name</th>
            <th>Category</th>
            <th>Brand</th>
            <th>UoM</th>
            <th>Store</th>
            <th>Prefix</th>
        </tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            @include('settings::pages.items_table_row')
        @empty
            <tr>
                <td colspan="4" align="center">No Data</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</main>
<footer>
    © Copyright <strong>goRMG-ERP</strong>. Developed by Skylark Soft Limited.
</footer>
</body>
</html>
