@include("hr::report-style")
<style>
    th, td {
        text-align: left !important;
        padding-left: 10px !important;
    }

    tr {
        height: 40px;
    }
</style>

<div class="row mb-4">
    <div class="col-md-8">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><b>First Name: </b> {{ $employee->first_name }}</li>
            <li class="list-group-item"><b>Last Name: </b> {{ $employee->last_name }}</li>
            <li class="list-group-item"><b>নাম (বাংলায়): </b> {{ $employee->name_bn }}</li>
            <li class="list-group-item"><b>Date Of Birth: </b> {{ $employee->date_of_birth->format('d M Y') }}</li>
            <li class="list-group-item">
                <b>Department: </b> {{ optional($employee->officialInfo)->departmentDetails->name }}</li>
            <li class="list-group-item">
                <b>Designation: </b> {{ optional($employee->officialInfo)->designationDetails->name }}</li>
            <li class="list-group-item"><b>Section: </b> {{ optional($employee->officialInfo)->sectionDetails->name }}
            </li>
        </ul>
    </div>
    <div class="col-md-4 text-center">
        @if($employee->document->photo)
            <img src="{{ asset('storage/photo/' . $employee->document->photo) }}"
                 alt="{{ $employee->screen_name . '\'s photo' }}" class="img-thumbnail">
        @else
            <img src="https://via.placeholder.com/250" alt="..." class="img-thumbnail">
        @endif
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h3>Basic Info</h3>
        <table class="table table-bordered">

            <tr>
                <th>Gender</th>
                <td>{{ ucfirst($employee->sex) }}</td>
            </tr>
            <tr>
                <th>Father Name</th>
                <td>{{ $employee->father_name }}</td>
            </tr>
            <tr>
                <th>পিতার নাম</th>
                <td>{{ $employee->father_name_bn }}</td>
            </tr>

            <tr>
                <th>Mother Name</th>
                <td>{{ $employee->mother_name }}</td>
            </tr>

            <tr>
                <th>মায়ের নাম</th>
                <td>{{ $employee->mother_name_bn }}</td>
            </tr>

            <tr>
                <th>NID</th>
                <td>{{ $employee->nid }}</td>
            </tr>

            <tr>
                <th>Nominee</th>
                <td>{{ $employee->nominee }}</td>
            </tr>

            <tr>
                <th>নমিনি (বাংলায়)</th>
                <td>{{ $employee->nominee_bn }}</td>
            </tr>

            <tr>
                <th>Nominee Relation</th>
                <td>{{ ucfirst($employee->nominee_relation) }}</td>
            </tr>

            <tr>
                <th>নমিনি সম্পর্ক</th>
                <td>{{ $employee->nominee_relation_bn }}</td>
            </tr>

            <tr>
                <th>Marital Status</th>
                <td>{{ ucfirst($employee->marital_status) }}</td>
            </tr>

            <tr>
                <th>Nationality</th>
                <td>{{ ucfirst($employee->nationality) }}</td>
            </tr>

            <tr>
                <th>জাতীয়তা</th>
                <td>{{ $employee->nationality_bn }}</td>
            </tr>

            <tr>
                <th>Religion</th>
                <td>{{ ucfirst($employee->religion) }}</td>
            </tr>

            <tr>
                <th>ধর্ম</th>
                <td>{{ $employee->religion_bn }}</td>
            </tr>

            <tr>
                <th>Present Address</th>
                <td>{{ $employee->present_address }}</td>
            </tr>

            <tr>
                <th>বর্তমান ঠিকানা</th>
                <td>{{ $employee->present_address_bn }}</td>
            </tr>

            <tr>
                <th>Permanent Address</th>
                <td>{{ $employee->permanent_address }}</td>
            </tr>

            <tr>
                <th>স্থায়ী ঠিকানা</th>
                <td>{{ $employee->permanent_address_bn }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h3>Official Info</h3>
        <table class="table table-bordered">
            <tr>
                <th>Grade</th>
                <td>{{ optional($employee->officialInfo)->grade->name }}</td>
            </tr>

            <tr>
                <th>Type</th>
                <td>{{ ucfirst(optional($employee->officialInfo)->type) }}</td>
            </tr>

            <tr>
                <th>Unique ID</th>
                <td>{{ optional($employee->officialInfo)->unique_id }}</td>
            </tr>

            <tr>
                <th>Code</th>
                <td>{{ optional($employee->officialInfo)->code }}</td>
            </tr>

            <tr>
                <th>BGMEA ID</th>
                <td>{{ optional($employee->officialInfo)->bgmea_id }}</td>
            </tr>

            <tr>
                <th>Punch Card ID</th>
                <td>{{ optional($employee->officialInfo)->punch_card_id }}</td>
            </tr>

            <tr>
                <th>Date Of Joining</th>
                <td>{{ optional($employee->officialInfo)->date_of_joining ? \Carbon\Carbon::parse(optional($employee->officialInfo)->date_of_joining)->format('d M Y') : '' }}</td>
            </tr>

            <tr>
                <th>যোগদানের তারিখ (বাংলায়)</th>
                <td>{{ optional($employee->officialInfo)->date_of_joining_bn ?? '' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h3>Salary Info</h3>
        <table class="table table-bordered" style="border-collapse: collapse">
            <tr>
                <th>Basic</th>
                <td>{{ $employee->salary->basic }}</td>
            </tr>
            <tr>
                <th>House Rent</th>
                <td>{{ $employee->salary->house_rent }}</td>
            </tr>
            <tr>
                <th>Food</th>
                <td>{{ $employee->salary->food }}</td>
            </tr>
            <tr>
                <th>Transport</th>
                <td>{{ $employee->salary->transport }}</td>
            </tr>
            <tr>
                <th>Medical</th>
                <td>{{ $employee->salary->medical }}</td>
            </tr>

            <tr>
                <th>Out Of City</th>
                <td>{{ $employee->salary->out_of_city }}</td>
            </tr>

            <tr>
                <th>Mobile Allowance</th>
                <td>{{ $employee->salary->mobile_allowence }}</td>
            </tr>

            <tr>
                <th>Attendance Bonus</th>
                <td>{{ $employee->salary->attendance_bonus }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h3>Job Experiences</h3>
        <table class="table table-bordered" style="border-collapse: collapse">
            <tr>
                <th>Company</th>
                <th>Designation</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Salary</th>
            </tr>
            @if(count($employee->jobExperiences))
                @foreach($employee->jobExperiences as $experience)
                    @if($experience->componay_name)
                        <tr>
                            <td>{{ $experience->company_name }}</td>
                            <td>{{ $experience->ex_job_designation }}</td>
                            <td>{{ $experience->from_date }}</td>
                            <td>{{ $experience->to_date }}</td>
                            <td>{{ $experience->ex_job_salary }}</td>
                        </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="5">No Data Found</td>
                </tr>
            @endif
        </table>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h3>Education Info</h3>

        <table class="table table-bordered" style="border-collapse: collapse">
            <tr>
                <th>Degree</th>
                <th>Institution</th>
                <th>Board</th>
                <th>Result</th>
                <th>Year</th>
            </tr>

            @if($employee->educations)
                @foreach($employee->educations as $education)
                    <tr>
                        <td>{{ $education->degree }}</td>
                        <td>{{ $education->institution }}</td>
                        <td>{{ $education->board }}</td>
                        <td>{{ $education->result }}</td>
                        <td>{{ $education->year }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">No Data Found</td>
                </tr>
            @endif

        </table>
    </div>
</div>

{{--<div class="row mb-4">--}}
{{--   <div class="col-md-12">--}}
{{--      <h3>Documents</h3>--}}
{{--      <table class="table table-bordered" style="border-collapse: collapse">--}}
{{--         @if($employee->document->nid)--}}
{{--            <tr>--}}
{{--               <th>NID</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/nid/' . $employee->document->nid) }}" target="_blank" download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->photo)--}}
{{--            <tr>--}}
{{--               <th>Photo</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/photo/' . $employee->document->photo) }}" target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->character_certificate)--}}
{{--            <tr>--}}
{{--               <th>Character Certificate</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/character_certificate/' . $employee->document->character_certificate) }}"--}}
{{--                     target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}


{{--         @if($employee->document->ssc_certificate)--}}
{{--            <tr>--}}
{{--               <th>SSC Certificate</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/ssc_certificate/' . $employee->document->ssc_certificate) }}"--}}
{{--                     target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->hsc_certificate)--}}
{{--            <tr>--}}
{{--               <th>HSC Certificate</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/hsc_certificate/' . $employee->document->hsc_certificate) }}"--}}
{{--                     target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->biodata)--}}
{{--            <tr>--}}
{{--               <th>Biodata</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/biodata/' . $employee->document->biodata) }}" target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->medical_certificate)--}}
{{--            <tr>--}}
{{--               <th>Medical Certificate</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/medical_certificate/' . $employee->document->medical_certificate) }}"--}}
{{--                     target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->signature)--}}
{{--            <tr>--}}
{{--               <th>Signature</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/signature/' . $employee->document->signature) }}" target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->masters)--}}
{{--            <tr>--}}
{{--               <th>Masters</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/masters/' . $employee->document->masters) }}" target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->hons)--}}
{{--            <tr>--}}
{{--               <th>Hons</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/hons/' . $employee->document->hons) }}" target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}

{{--         @if($employee->document->others)--}}
{{--            <tr>--}}
{{--               <th>Others</th>--}}
{{--               <td>--}}
{{--                  <a href="{{ asset('storage/others/' . $employee->document->others) }}" target="_blank"--}}
{{--                     download>Download</a>--}}
{{--               </td>--}}
{{--            </tr>--}}
{{--         @endif--}}
{{--      </table>--}}
{{--   </div>--}}
{{--</div>--}}

