<style>
    @import url('https://fonts.maateen.me/solaiman-lipi/font.css');
    * {
        font-family: 'SolaimanLipi', sans-serif;
    }



    .main-area {
        margin: 10px 0;
    }
    .outer-border {
        height: 281px;
    }
    .content {
        height: 210px;
        float: left;
        overflow: hidden;
        border: 7px solid blue;
        box-sizing: border-box;
    }
    .card-area {
        height: 200px;
        width: 320px;
        border: 2px solid #000;
        box-sizing: border-box;
        border-radius: 0;
        padding: 10px;
    }
    
    table tr td {
        text-align: left;
        border: none !important;
        font-size: 10px !important;
        margin-top: 0 !important;
        padding:0 !important; 
    }

    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th {
        padding-left: 0; 
        padding-right: 0;
    }

    @media print {
        @page {
            size: portrait;
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
            -webkit-font-smoothing: antialiased;
        }

        * {
            padding: 0;
            margin: 0;
            font-size: 15px!important;
            font-weight: 600;
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
                <div class="outer-border">
                    <div class="content">
                        
                        <div class="card-area">
                            <table class="table" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td colspan="3" style="text-align: center;">
                                            <h5 style="font-size: 12px !important;">{{ factoryName() }}</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span style="font-size: 13px !important">{{ $employee->first_name." ".$employee->last_name }}</span>
                                        </td>
                                        <td rowspan="5" style="width: 20%"> 
                                            <img style="width: 80px; float: right; height:75px" class="employee_photo" 
                                            src="{{ (Storage::exists('public/photo/'.$employee->document->photo) && $employee->document->photo) ? asset('/')."storage/public/photo/".$employee->document->photo : 'https://via.placeholder.com/130' }}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 28%">Designation</td>
                                        <td style="width: 2%;">:</td>
                                        <td style="width: 70%;">{{ $employee->officialInfo->designationDetails->name }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Department</td>
                                        <td>:</td>
                                        <td>{{ $employee->officialInfo->departmentDetails->name }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Join Date</td>
                                        <td>:</td>
                                        <td>{{ ($employee->officialInfo->date_of_joining_bn != null) ? \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->officialInfo->date_of_joining_bn) : \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn(date('d/m/Y', strtotime($employee->officialInfo->date_of_joining))) }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Issue Date</td>
                                        <td>:</td>
                                        <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn(date('d/m/Y')) }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Work Type</td>
                                        <td>:</td>
                                        <td></td>

                                        <td style="text-align: center; ">
                                            <span style="font-size: 20px !important; font-weight: bold;">
                                                {{ $employee->officialInfo->unique_id ? \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->officialInfo->unique_id) : '' }}
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <img class="employee_signature" 
                                                 style="width: 100px!important; height: 30px !important; position: relative; top: 0; right: 0;"
                                                 src="{{ (Storage::exists('public/signature/'.$employee->document->signature) && $employee->document->signature) ? asset('/')."storage/signature/".$employee->document->signature : 'https://via.placeholder.com/156x54' }}"/>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; position: relative; top: 4px; right: 15px;">
                                            @if($employee->officialInfo->type == 'worker')
                                                <img class="authorized_signature" height="20" 
                                                    src="{{ asset('images/worker_authorized_signature.jpg') }}"/>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    <tr style="margin-top: 20px">
                                        <td style="position: relative; top: -8px">__________________</td> 
                                        <td colspan="3" style="text-align: right; position: relative; top: -8px" >__________________</td> 
                                    </tr>

                                    <tr style="margin-top: 20px">
                                        <td style="position: relative; top: -13px">Employee's Signature</td> 
                                        <td colspan="3" style="text-align: right; position: relative; top: -13px" >Authorized Signature</td> 
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
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
