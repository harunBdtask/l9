<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    .v-align-top td, .v-algin-top th {
        vertical-align: top;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        page-break-before: avoid;
    }

    table, th, td {
        border: 1px solid black;
    }


    th {
        padding: 0;
    }

    td {
        padding: 1px 2px;
    }

    table.borderless {
        border: none;
        text-align: center;
    }

    table.border {
        border: 1px solid black;
        width: 20%;
        margin-left: auto;
        margin-right: auto;
    }

    .borderless td, .borderless th {
        border: none;
    }

    footer {
        text-align: left;
        font-size: 10px;
        padding-top: 40px;
    }
</style>
<body>
<footer>
    <div class="signature">
        <table class="borderless">
            <tbody>
                <tr>
                    <td style="padding: 0 30px 0 30px">
                        <p style="border: 1px solid black;font-size: 16px;">Prepared By</p>
                    </td>
                    <td style="padding: 0 30px 0 30px">
                        <p style="border: 1px solid black;font-size: 16px;">Shift In-Charge</p>
                    </td>
                    <td style="padding: 0 30px 0 30px">
                        <p style="border: 1px solid black;font-size: 16px;">Approved By</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr style="background: black"/>
    System generated report | © Copyright - goRMG ERP. Produced by Skylark Soft Limited | Phone: {{ factoryPhone() }}
</footer>
</body>
</html>
