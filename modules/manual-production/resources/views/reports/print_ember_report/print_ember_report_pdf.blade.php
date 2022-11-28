<h2 style="margin: 0" class="center">{{ factoryName() }}</h2>
<p style="margin: 0" class="center">{{ factoryAddress() }}</p>
<p class="center">Print Embellishment Report</p>
@includeIf('manual-production::reports.print_ember_report.master')

<style>
    .center{
        text-align: center;
    }

    table, td, th {
        border: 1px solid black;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table {
        margin-top: 20px;
    }
</style>
