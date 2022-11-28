    <style type="text/css">
    @import url('https://fonts.maateen.me/solaiman-lipi/font.css');

    * {
        font-family: 'SolaimanLipi', sans-serif;
    }

    .main-area {
        margin: 10px 0;
    }
    .outer-border {
        border: 3px solid #000;
        height: 277px;
        width: 443px;
    }
    .content {
        height: 280px;
        box-sizing: border-box;
    }
    
    .card-area {
        height: 211px;
        width: 333px;
        border: 3px solid #000;
        box-sizing: border-box;
        border-radius: 0;
    }

    .card-area span {
        padding: 0 10px;
    }

    table tr td {
        text-align: left;
        border: none !important;
        font-size: 10px !important;
        height: 0% !important;
        margin-top: 0 !important;
        padding-top:0 !important;
        padding-left: 10px !important;
    }
    .border {
        margin: 2px 0;
        border-top: 3px solid #000;
    }

    .table {
        margin-bottom: 0;
    }
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th {
        padding-left: 0; 
        padding-right: 0;
        padding-bottom: 0px;
        line-height: 11px;
    }

    @media print {
        * {
            padding: 0;
            margin: 0;
            -webkit-font-smoothing: antialiased;
            font-size: 15px!important;
            font-weight: 600;
        }

        @page {
            size: portrait;
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        }

        .row {
            padding: 0 !important;
            margin: 0 !important;
            page-break-after: always;
        }


    }

</style>
@if($employees && $employees->count())
    @php
        $counter = 0;
    @endphp
    @foreach($employees->sortBy('officialInfo.unique_id') as $key => $employee)
    <div class="main-area">
        <div class="row">
            <div class="content">
                <div class="card-area">
                    <table class="table" style="width: 100%;">
                        <tbody>
                            <tr>
                                <span style="font-size: 13px !important;">If found please return this card to.</span> <br>
                                <span style="font-size: 14px !important; font-weight: bold;">{{ factoryName() }}</span> <br>
                                <span style="font-size: 10px !important;">Address</span>
                                <span>____________________</span>
                            </tr>

                            <div class="border">
                                <tr>
                                    <td>
                                        <span style="font-size: 14px !important; padding: 0">
                                            Permanent Address-
                                        </span>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Care of</td>
                                    <td>:</td>
                                    <td>{{ $employee->father_name_bn }}</td>
                                </tr>
                                <tr>
                                    <td>Village / House No.</td>
                                    <td>:</td>
                                    <td>{{ $employee->permanent_address_bn }}</td>
                                </tr>
                                <tr>
                                    <td>Post Office</td>
                                    <td>:</td>
                                    <td>{{ $employee->zilla_bn }} - {{ $employee->post_code_bn }}</td>
                                </tr>
                                <tr>
                                    <td>Police Station</td>
                                    <td>:</td>
                                    <td>{{ $employee->upazilla_bn }}</td>
                                </tr>
                                <tr>
                                    <td>District</td>
                                    <td>:</td>
                                    <td>{{ $employee->zilla_bn }}</td>
                                </tr>
                                <tr>
                                    <td>Emergency Contact No</td>
                                    <td>:</td>
                                    <td>{{ $employee->emergency_contact_no_bn }}</td>
                                </tr>
                                <tr>
                                    <td>National ID No.</td>
                                    <td>:</td>
                                    <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->nid) }}</td>
                                </tr>
                                <tr>
                                    <td>Validity of ID Card</td>
                                    <td>:</td>
                                    @php
                                        $validity_date = \Carbon\Carbon::now()->addYear(4)->format('d/m/Y');
                                    @endphp
                                    <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($validity_date) }}</td>
                                </tr>
                                <tr>
                                    <td>Blood Group</td>
                                    <td>:</td>
                                    <td>{{ $employee->blood_group }}</td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="row">
        <h4 class="text-center">No Data Found</h4>
    </div>
@endif
