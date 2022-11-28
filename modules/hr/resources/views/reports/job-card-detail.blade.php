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
    .reportTable td {
        border: 1px solid #000;
    }

    .table td, .table th {
        padding: 0.1rem;
        vertical-align: middle;
    }

    .semiBorderLessReportTable {
        margin-bottom: 1rem;
        width: 100%;
        max-width: 100%;
        font-size: 9pt;
        border-collapse: collapse;
        border: 1px solid #1d1d1d;
    }

    .semiBorderLessReportTable thead,
    .semiBorderLessReportTable tbody,
    .semiBorderLessReportTable th {
        padding: 3px;
        font-size: 9pt;
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
            margin: 5mm;
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
            margin-top: 200px !important;
        }

    }
</style>
@include('hr::reports.include.header')

<div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-borderless">
                <tr>
                    <th style="text-align: left">Name:</th>
                    <td style="text-align: left">{{ $employee->screen_name }}</td>
                    <th style="text-align: left">Joining Date:</th>
                    <td style="text-align: left">{{ \Carbon\Carbon::parse($employee->officialInfo->date_of_joining)->format('d-M-y') }}</td>
                    <th style="text-align: left">Designation</th>
                    <td style="text-align: left">{{ $employee->officialInfo->designationDetails->name }}</td>
                    <th style="text-align: left">Section:</th>
                    <td style="text-align: left">{{ $employee->officialInfo->sectionDetails->name }}</td>
                </tr>

                <tr>
                    <th style="text-align: left">For the month of:</th>
                    <td style="text-align: left">{{ date("F", mktime(0, 0, 0, $month, 10)) . '-' . $year }}</td>
                    <th style="text-align: left">Period Of:</th>
                    <td style="text-align: left">
                        {{ \Carbon\Carbon::create($year, $month, 1)->format('d-M-y') }}
                        To
                        {{ \Carbon\Carbon::create($year, $month, 1)->lastOfMonth()->format('d-M-y') }}
                    </td>
                    <th style="text-align: left">Unique: ID</th>
                    <td style="text-align: left">{{ $employee->officialInfo->unique_id }}</td>
                    <th style="text-align: left">Code:</th>
                    <td style="text-align: left">{{ $employee->officialInfo->code }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <?php
        $totalOtHours = 0;
        $totalLate = 0;
        $presentDays = 0;
        $totalWeeklyHoliday = 0;
        $totalMainOtMinutes = 0;
        $totalExtraOtMinutes = 0;
        $totalOtMinutes = 0;
        ?>

        <div class="col-md-12">
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
                    <th>Extra <br> OT</th>
                    <th>Late</th>
                    <th>Type</th>
                </tr>
                @php
                    $total_minute = 0;
                    $total_main_ot = 0;
                    $total_extra_ot = 0;
                    $total_late = 0;
                    $total_present = 0;
                @endphp
                @if($attendances && count($attendances))
                    @foreach($attendances as $att)
                        @php
                            $total_minute += $att['minute'];
                            $total_main_ot += $att['main_ot'];
                            $total_extra_ot += $att['extraot'];
                            $total_late += $att['late'];
                            $total_present += $att['type'] == 'Present' ? 1 : 0;
                        @endphp
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($att['date'])->format('d/m/Y') }}</td>
                            <td>{{ $att['day'] }}</td>
                            <td>{{ strtoupper($att['intime'])  }}</td>
                            <td>{{ strtoupper($att['outtime']) }}</td>
                            <td>{{ $att['totalhour'] }}</td>
                            <td>{{ $att['lunchhour'] }}</td>
                            <td>{{ $att['minute'] }}</td>
                            <?php
                            $totalOtMinutes += $att['main_ot'] * 60;
                            $totalOtMinutes += $att['extraot'] * 60;
                            ?>
                            <td>{{ $att['main_ot'] }}</td>
                            <td>{{ $att['extraot'] }}</td>
                            <td>{{ $att['late'] }}</td>
                            <td>{{ $att['type'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="6"></th>
                        <th>Min-</th>
                        <th>{{ $total_minute }}</th>
                        <th></th>
                        <th>Late:</th>
                        <th>{{ $total_late }}</th>
                        <th>&nbsp;</th>
                    </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="spacer-top" style="display: flex; justify-content: space-between">
                <div style="width: 30%">
                    <table class="semiBorderLessReportTable">
                        @php
                            $holiday_total_hour = 0;
                            $holiday_count = 0;
                        @endphp
                        @foreach($attendances as $att)
                            @if(\Carbon\Carbon::parse($att['date'])->isFriday())
                                @php
                                    $totalOtMinutes += $att['minute'];
                                    $total_hour = $att['minute'] ? round((($att['minute'] - 60) / 60), 3) : 0;
                                    $holiday_total_hour += $total_hour;
                                    $holiday_count += 1;
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($att['date'])->format('d/m/Y') }}</td>
                                    <td>{{ $att['day'] }}</td>
                                    <td>{{ strtoupper($att['intime'])  }}</td>
                                    <td>{{ strtoupper($att['outtime']) }}</td>
                                    <td>{{ $total_hour }}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
                            <th>{{ $holiday_total_hour }}</th>
                        </tr>
                        <tr>
                            <th colspan="3">Weekly Holiday : {{ $holiday_count }}</th>
                            <th colspan="2">&nbsp;</th>
                        </tr>
                        <tr>
                            <th colspan="3">Total Working Days : {{ $totalWorkingDays }}</th>
                            <th colspan="2">&nbsp;</th>
                        </tr>
                        <tr>
                            <th colspan="3">Total Pay Days : {{ count($attendances) - $totalAbsents }}</th>
                            <th colspan="2">&nbsp;</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div style="width: 30%">
                    <table class="borderLessReportTable">
                        <tbody>
                        <tr>
                            <th class="brdr-btm-1">Present Days:</th>
                            <td class="brdr-btm-1">{{ $total_present }}</td>
                        </tr>
                        <tr>
                            <th>Main OT:</th>
                            <td>{{ $total_main_ot }}</td>
                        </tr>
                        <tr>
                            <th>Extra OT:</th>
                            <td>{{ $total_extra_ot }}</td>
                        </tr>
                        <tr>
                            <th>Holiday OT:</th>
                            <td>{{ $holiday_total_hour }}</td>
                        </tr>
                        <tr>
                            <th class="brdr-btm-1">Total OT Minutes:</th>
                            <td class="brdr-btm-1">{{ ($total_main_ot + $total_extra_ot + $holiday_total_hour) * 60 }}</td>
                        </tr>
                        <tr>
                            <th>Total OT:</th>
                            <td>{{ $total_main_ot + $total_extra_ot + $holiday_total_hour}}
                                ( {{ ($total_main_ot + $total_extra_ot + $holiday_total_hour) * 60 }} )
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
