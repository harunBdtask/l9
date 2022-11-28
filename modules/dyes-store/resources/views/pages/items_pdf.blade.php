<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>


    <style>
        .bg-danger {
            color: #fff !important;
            background-color: #ea070087 !important;
        }

        .bg-gwhite {
            background: ghostwhite;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .padding-1 {
            padding: 1%;
        }

        .reportTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }

        .reportTable thead,
        .reportTable tbody,
        .reportTable th {
            padding: 3px;
            font-size: 12px;
            text-align: center;
        }

        .reportTable th,
        .reportTable td {
            border: 1px solid #000;
        }

        .table td, .table th {
            padding: 0.1rem;
            vertical-align: middle;
        }

        @page {
            margin: 100px 35px 35px 35px;!important;
        }

        header {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            text-align: center;
            height: 150px;
        }

        footer {
            position: fixed;
            bottom: -50px;
            font-size: 12px;
            left: 0;
            right: 0;
            text-align: center;
            height: 50px;
        }

        header h4 {
            margin: 2px 0 2px 0;
        }

        header h2 {
            margin-bottom: 2px;
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
            @include('dyes-store::pages.items_table_row')
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
