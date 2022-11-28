<style>
    @import url('https://fonts.maateen.me/solaiman-lipi/font.css');

    * {
        font-family: 'SolaimanLipi', sans-serif;
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
    .reportTable td,
    .reportTable tfoot {
        border: 1px solid #000;
    }

    .reportTable tfoot > tr > th,
    .reportTable tfoot > tr > td {
        border: none !important;
    }

    .table td, .table th {
        padding: 0.1rem;
        vertical-align: middle;
    }

    .semiBorderLessReportTable {
        margin-bottom: 1rem;
        width: 100%;
        max-width: 100%;
        font-size: 12px;
        border-collapse: collapse;
        border: 1px solid #1d1d1d;
    }

    .semiBorderLessReportTable thead,
    .semiBorderLessReportTable tbody,
    .semiBorderLessReportTable th {
        padding: 3px;
        font-size: 12px;
        text-align: center;
    }

    .semiBorderLessReportTable th,
    .semiBorderLessReportTable td {
        border: none;
    }

    .semiBorderLessReportTable tfoot > tr > th {
        border: 1px solid #1d1d1d;
    }

    .borderLessReportTable {
        margin-bottom: 1rem;
        width: 100%;
        max-width: 100%;
        font-size: 12px;
        border-collapse: collapse;
        border: none;
    }

    .borderLessReportTable thead,
    .borderLessReportTable tbody,
    .borderLessReportTable th {
        padding: 3px;
        font-size: 12px;
        text-align: center;
    }

    .borderLessReportTable th,
    .borderLessReportTable td {
        border: none;
    }

    .text-center {
        text-align: center;
    }

    .brdr-btm-1 {
        border-bottom: 1px solid #1d1d1d !important;
    }

    @media print {
        @page {
            size: A4 portrait;
        }

        .main {
            margin: 10px;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        table {
            page-break-inside: auto
        }

        .spacer-top {
            margin-top: 150px !important;
        }

    }
</style>
@include('hr::reports.include.header')

<div>
        <table class="table table-borderless">
            <tr>
                <th>Name:</th>
                <td>{{ $employee->screen_name }}</td>
                <th>Joining Date:</th>
                <td>{{ \Carbon\Carbon::parse($employee->officialInfo->date_of_joining)->format('d-M-y') }}</td>
                <th>Designation</th>
                <td>{{ $employee->officialInfo->designationDetails->name }}</td>
                <th>Section:</th>
                <td>{{ $employee->officialInfo->sectionDetails->name }}</td>
            </tr>

            <tr>
                <th>For the month of:</th>
                <td>{{ date("F", mktime(0, 0, 0, $month, 10)) . '-' . $year }}</td>
                <th>Period Of:</th>
                <td>
                    {{ \Carbon\Carbon::create($year, $month, 1)->format('d-M-y') }}
                    To
                    {{ \Carbon\Carbon::create($year, $month, 1)->lastOfMonth()->format('d-M-y') }}
                </td>
                <th>Unique: ID</th>
                <td>{{ $employee->officialInfo->unique_id }}</td>
                <th>Code:</th>
                <td>{{ $employee->officialInfo->code }}</td>
            </tr>
        </table>

        <table class="reportTable">
            <tr>
                <th>S.L</th>
                <th>Date</th>
                <th>Day</th>
                <th>In Time</th>
                <th>Out Time</th>
                <th>Total Hour</th>
                <th>Lunch Hour</th>
                <th>Minute</th>
                <th>Main <br> OT</th>
                <th>Late</th>
                <th>Type</th>
            </tr>
            @php
                $total_minute = 0;
                $total_main_ot = 0;
                $total_late = 0;
                $total_present = 0;
                $holiday_count = 0;
            @endphp
            @if($attendances && count($attendances) > 0)
                @foreach($attendances as $att)
                    @php
                        $total_minute += $att['minute'];
                        $total_main_ot += $att['main_ot'];
                        $total_late += $att['late'];
                        $total_present += $att['type'] == 'Present' ? 1 : 0;
                        $holiday_count += \Carbon\Carbon::parse($att['date'])->isFriday() ? 1 : 0;
                    @endphp
                    <tr>
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($att['date'])->format('d/m/Y') }}</td>
                        <td>{{ $att['day'] }}</td>
                        <td>{{ strtoupper($att['intime'])  }}</td>
                        <td>{{ strtoupper($att['outtime']) }}</td>
                        <?php
                        $inTime = \Carbon\Carbon::parse($att['intime']);
                        $outTime = \Carbon\Carbon::parse($att['outtime']);
                        $totalHour = $inTime->diffInSeconds($outTime);
                        ?>
                        @if($att['intime'])
                            <td>{{ gmdate('H:i:s', $totalHour) }}</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{ $att['lunchhour'] }}</td>
                        <td>{{ $att['minute'] }}</td>
                        <td>{{ $att['main_ot'] }}</td>
                        <td>{{ $att['late'] }}</td>
                        <td>{{ $att['type'] }}</td>
                    </tr>
                @endforeach
                <tfoot>
                <tr>
                    <th colspan="3">Month Days : {{ $monthDays }}</th>
                    <th colspan="4">Holidays : {{ $totalHolidays }}</th>
                    <th>Total O.T Hours</th>
                    <th>{{ $total_main_ot }}</th>
                    <th>{{ $total_late }}</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="3">Present Days : {{ $total_present }}</th>
                    <th colspan="4"></th>
                    <th colspan="4">&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="3">Total Pay Days : {{ count($attendances) - $totalAbsents }}</th>
                    <th colspan="8">&nbsp;</th>
                </tr>
                </tfoot>
            @endif
        </table>
</div>
