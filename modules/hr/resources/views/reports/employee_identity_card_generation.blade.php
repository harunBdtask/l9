<style>
    @import url('https://fonts.maateen.me/solaiman-lipi/font.css');
    * {
        font-family: 'SolaimanLipi', sans-serif;
    }


    /* Front */


    .main-area {
        float: left;
        overflow: hidden;
        width: 19%;
        position: relative;
    }
    .bg-image {
        opacity: 0.3;
        position: absolute;
        width: 45%;
        height: 75px;
        top: 70px;
        left: 90px;
    }
    .bg-image img {
        width: 100%;
        height: auto;
    }
    .outer-border {
        height: 213px;
        position: relative;
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
        font-size: 9px !important;
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
            /* page-break-after: always; */
            page-break-inside:avoid;
        }

        .main-area {
            float: left;
            overflow: hidden;
            width: 50% !important;
        }
        .main-area .card-area h5 {
            margin-bottom: 0 !important;
        }

    }


    /* Back  */

    .back-main-area {
        margin: 10px 0;
        width: 19%;
        overflow: hidden;
    }
    .back-outer-border {
        border: 3px solid #000;
        height: 277px;
        width: 443px;
    }
    .back-content {
        height: 213px;
        box-sizing: border-box;
    }

    .back-card-area {
        height: 211px;
        width: 333px;
        border: 3px solid #000;
        box-sizing: border-box;
        border-radius: 0;
    }

    .back-card-area span {
        padding: 0 10px;
    }

    .back-card-area table tr td {
        text-align: left;
        border: none !important;
        font-size: 10px !important;
        height: 0% !important;
        margin-top: 0 !important;
        padding-top:0 !important;
        padding-left: 10px !important;
    }
    .back-border {
        margin: 2px 0;
        border-top: 3px solid #000;
    }

    .back-card-area .table {
        margin-bottom: 0;
    }
    .back-card-area .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th {
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
            /* page-break-after: always; */
        }

        .back-main-area {
            margin: 10px 0;
            width: 50% !important;
            overflow: hidden;
        }


    }


</style>
@if($employees && $employees->count())
    @php
        $counter = 0;
    @endphp
    @foreach($employees->sortBy('officialInfo.unique_id') as $key => $employee)
        @if($employee->officialInfo->type == "worker")
        {{-- Type = worker, Bangla identity card --}}
            <div class="row">
                {{-- front --}}
                <div class="main-area">
                    <img class="bg-image" src="{{ asset('/')."storage/factory_image/". factoryImage() }}" alt="background image">
                    <div class="">
                        <div class="outer-border">
                            <div class="content">

                                <div class="card-area">
                                    <table class="table" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td colspan="3" style="">
                                                    <h5 style="font-size: 14px !important; margin: 0 0 0 85px;">
                                                        {{ factoryNameBn() }}
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="font-size: 13px !important">{{ $employee->name_bn }}</span>
                                                </td>
                                                <td rowspan="5" style="width: 20%">
                                                    <img style="width: 70px; float: right; height:75px" class="employee_photo"
                                                    src="{{ (Storage::exists('photo/'.$employee->document->photo) && $employee->document->photo) ? asset('/')."storage/photo/".$employee->document->photo : 'https://via.placeholder.com/130' }}"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 38%">পদবী</td>
                                                <td style="width: 2%;">:</td>
                                                <td style="width: 50%;">{{ $employee->officialInfo->designationDetails->name_bn }}</td>
                                            </tr>

                                            <tr>
                                                <td>বিভাগ</td>
                                                <td>:</td>
                                                <td>{{ $employee->officialInfo->departmentDetails->name_bn }}</td>
                                            </tr>

                                            <tr>
                                                <td>সেকশন</td>
                                                <td>:</td>
                                                <td>{{ $employee->officialInfo->sectionDetails->name_bn }}</td>
                                            </tr>

                                            <tr>
                                                <td>যোগদান তারিখ</td>
                                                <td>:</td>
                                                <td>{{ ($employee->officialInfo->date_of_joining_bn != null) ? \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->officialInfo->date_of_joining) : \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->officialInfo->date_of_joining) }}</td>
                                            </tr>

                                            <tr>
                                                <td>প্রদানের তারিখ</td>
                                                <td>:</td>
                                                <td>{{ ($employee->officialInfo->date_of_joining_bn != null) ? \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->officialInfo->date_of_joining) : \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->officialInfo->date_of_joining) }}</td>
                                            </tr>

                                            <tr>
                                                <td>কাজের ধরন</td>
                                                <td>:</td>
                                                <td>{{ $employee->officialInfo->workType->name_bn }}</td>

                                                <td style="text-align: center; ">
                                                    <span style="font-size: 16px !important; font-weight: bold; position: relative; top: -10px;">
                                                        {{ $employee->officialInfo->unique_id ? \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->officialInfo->unique_id) : '' }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <img class="employee_signature"
                                                        style="width: 90px !important; height: 25px !important; position: relative; top: 0; right: 0;"
                                                        src="{{ (Storage::exists('signature/'.$employee->document->signature) && $employee->document->signature) ? asset('/')."storage/signature/".$employee->document->signature : 'https://via.placeholder.com/156x54' }}"/>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align: right; position: relative; top: 4px; right: 15px;">
                                                    <img style="margin-top: -10px !important" class="authorized_signature" width="60" height="30"
                                                        src="{{ asset('images/worker_authorized_signature.png') }}"/>
                                                </td>
                                            </tr>

                                            <tr style="margin-top: 0 !important">
                                                <td style="position: relative; top: -12px">_______________</td>
                                                <td colspan="3" style="text-align: right; position: relative; top: -12px" >_______________</td>
                                            </tr>

                                            <tr style="margin-top: 0 !important">
                                                <td style="position: relative; top: -17px">শ্রমিকের স্বাক্ষর </td>
                                                <td colspan="3" style="text-align: right; position: relative; top: -17px" >মালিক/ব্যবস্থাপক</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- back --}}
                <div class="back-main-area">
                    <div class="">
                        <div class="back-content">
                            <div class="back-card-area">
                                <table class="table" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <span style="font-size: 11px !important; margin-top: 2px !important; display:block;">
                                                হারিয়ে যাওয়া কার্ডটি যদি পাওয়া যায় তাহলে অনুগ্রহ করে নিম্ন ঠিকানায় এই কার্ডটি ফেরত দিন ।
                                                +8801796-333742
                                            </span>
                                            <span style="font-size: 12px !important; font-weight: bold;">{{ factoryNameBn() }}</span> <br>
                                            <span style="font-size: 10px !important;">ঠিকানা: </span>
                                            <span style="font-size: 10px !important;">{{ factoryAddressBn() }}</span>
                                        </tr>

                                        <div class="back-border">
                                            <tr>
                                                <td>
                                                    <span style="font-size: 12px !important; padding: 0">
                                                        স্থায়ী ঠিকানা
                                                    </span>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>স্বামী/পিতার নাম</td>
                                                <td>:</td>
                                                <td>{{ $employee->father_name_bn }}</td>
                                            </tr>
                                            <tr>
                                                <td>গ্রাম</td>
                                                <td>:</td>
                                                <td>{{ $employee->village_bn }}</td>
                                            </tr>
                                            <tr>
                                                <td>পোস্ট অফিস</td>
                                                <td>:</td>
                                                <td>{{ $employee->post_office_bn }}</td>
                                            </tr>
                                            <tr>
                                                <td>থানা</td>
                                                <td>:</td>
                                                <td>{{ $employee->upazilla->name_bn ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>জেলা</td>
                                                <td>:</td>
                                                <td>{{ $employee->zilla->name_bn ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>জরুরী যোগাযোগ নং</td>
                                                <td>:</td>
                                                <td>{{ $employee->emergency_contact_no_bn }}</td>
                                            </tr>
                                            <tr>
                                                <td>জাতীয় পরিচয়পত্র নম্বর</td>
                                                <td>:</td>
                                                <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->nid) }}</td>
                                            </tr>
                                            <tr>
                                                <td>পরিচয়পত্রের মেয়াদ</td>
                                                <td>:</td>
                                                <td>চাকুরীর মেয়াদকালীন</td>
                                            </tr>
                                            <tr>
                                                <td>রক্তের গ্রুপ</td>
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
            </div>
        @elseif ($employee->officialInfo->type == "staff")
        {{-- Type = staff, English identity card --}}
            <div class="row">
                {{-- front --}}
                <div class="main-area">
                    <img class="bg-image" src="{{ asset('/')."storage/factory_image/". factoryImage() }}" alt="background image">
                    <div class="">
                        <div class="outer-border">
                            <div class="content" style="border: 7px solid #20cf15 !important;">

                                <div class="card-area">
                                    <table class="table" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td colspan="3">
                                                    <h5 style="font-size: 14px !important; margin: 0 0 0 56px;">
                                                        {{ factoryName() }}
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <span style="font-size: 13px !important">{{ $employee->first_name." ".$employee->last_name }}</span>
                                                </td>
                                                <td rowspan="5" style="width: 20%">
                                                    <img style="width: 70px; float: right; height:75px" class="employee_photo"
                                                    src="{{ (Storage::exists('photo/'.$employee->document->photo) && $employee->document->photo) ? asset('/')."storage/photo/".$employee->document->photo : 'https://via.placeholder.com/130' }}"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 38% !important;">Designation</td>
                                                <td style="width: 2%;">:</td>
                                                <td style="width: 50% !important;">{{ $employee->officialInfo->designationDetails->name }}</td>
                                            </tr>

                                            <tr>
                                                <td>Department</td>
                                                <td>:</td>
                                                <td>{{ $employee->officialInfo->departmentDetails->name }}</td>
                                            </tr>

                                            <tr>
                                                <td>Section</td>
                                                <td>:</td>
                                                <td>{{ $employee->officialInfo->sectionDetails->name }}</td>
                                            </tr>

                                            <tr>
                                                <td>Join Date</td>
                                                <td>:</td>
                                                <td>{{ date('d-m-Y', strtotime($employee->officialInfo->date_of_joining)) }}</td>
                                            </tr>

                                            <tr>
                                                <td>Issue Date</td>
                                                <td>:</td>
                                                <td>{{ date('d-m-Y', strtotime($employee->officialInfo->date_of_joining)) }}</td>
                                            </tr>

                                            <tr>
                                                <td>Work Type</td>
                                                <td>:</td>
                                                <td></td>

                                                <td style="text-align: center; ">
                                                    <span style="font-size: 16px !important; font-weight: bold; position: relative; top: -10px;">
                                                        {{ $employee->officialInfo->unique_id }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <img class="employee_signature"
                                                        style="width: 90px !important; height: 25px !important; position: relative; top: 0; right: 0;"
                                                        src="{{ (Storage::exists('signature/'.$employee->document->signature) && $employee->document->signature) ? asset('/')."storage/signature/".$employee->document->signature : 'https://via.placeholder.com/156x54' }}"/>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td style="text-align: right; position: relative; top: 4px; right: 15px;">
                                                    <img style="margin-top: -10px !important" class="authorized_signature"  width="60" height="30"
                                                        src="{{ asset('images/worker_authorized_signature.png') }}"/>
                                                </td>
                                            </tr>

                                            <tr style="margin-top: 0 !important">
                                                <td style="position: relative; top: -12px">_______________</td>
                                                <td colspan="3" style="text-align: right; position: relative; top: -12px" >_______________</td>
                                            </tr>

                                            <tr style="margin-top: 0 !important">
                                                <td style="position: relative; top: -17px">Employee's Signature</td>
                                                <td colspan="3" style="text-align: right; position: relative; top: -17px" >Owner/Director</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- back --}}
                <div class="back-main-area">
                    <div class="">
                        <div class="back-content">
                            <div class="back-card-area">
                                <table class="table" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <span style="font-size: 13px !important;">
                                                If found please return this card to +8801796-333742
                                            </span> <br>
                                            <span style="font-size: 12px !important; font-weight: bold;">{{ factoryName() }}</span> <br>
                                            <span style="font-size: 10px !important;">Address: </span>
                                            <span style="font-size: 10px !important;">{{ factoryAddress() }}</span>
                                        </tr>

                                        <div class="back-border">
                                            <tr>
                                                <td>
                                                    <span style="font-size: 14px !important; padding: 0">
                                                        Permanent Address
                                                    </span>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Care of</td>
                                                <td>:</td>
                                                <td>{{ $employee->father_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Village / House No.</td>
                                                <td>:</td>
                                                <td>{{ $employee->village }}</td>
                                            </tr>
                                            <tr>
                                                <td>Post Office</td>
                                                <td>:</td>
                                                <td>{{ $employee->postOfice->postOffice ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Police Station</td>
                                                <td>:</td>
                                                <td>{{ $employee->upazilla->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>District</td>
                                                <td>:</td>
                                                <td>{{ $employee->zilla->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Emergency Contact No</td>
                                                <td>:</td>
                                                <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::bn2en($employee->emergency_contact_no_bn) }}</td>
                                            </tr>
                                            <tr>
                                                <td>National ID No.</td>
                                                <td>:</td>
                                                <td>{{ $employee->nid }}</td>
                                            </tr>
                                            <tr>
                                                <td>Validity of ID Card</td>
                                                <td>:</td>
                                                <td>Till Employment</td>
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
            </div>
        @endif

    @endforeach
@else
    <div class="row">
        <h4 class="text-center">No Data Found</h4>
    </div>
@endif
