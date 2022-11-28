<style>

    .table td, .table th {
        vertical-align: middle;
        text-align: left;
    }
    .list-style-none {
        list-style: none;
    }
    ul {
        margin-bottom: 0;
    }
    .address {
        border: 1px solid #000 !important;
    }
    .employeement-rules tr.border {
        border: 1px solid #000 !important;
    }
    tr.outer-border {
        border: 1px solid #000 !important;
    }
    .footer-text table {
        margin-top: 40px !important;
    }
    @media print {
        @page {
            color: #000 !important;
            size: A4 portrait;
        }

        table th, table td{
            font-size: 12px !important;
        }
        table {
            line-height: 1.1em;
        }
        * {
            color: #000;
            padding: 0;
            margin: 0;
            font-size: 12px !important;
            -webkit-print-color-adjust: exact;
            -webkit-font-smoothing: antialiased;
        }

    }

</style>

<div class="main">

    <div class="company-details" style="text-align: center; height: 90px;">

            @if(factoryImage() && Storage::exists('factory_image/'.factoryImage()))
                <img
                    src="{{ asset('/')."storage/factory_image/". factoryImage() }}"
                    alt="Logo" style="float:left; overflow: hidden; width: 100px; height: auto;">
            @else
                <img src="{{ asset('images/no_image.png') }}"
                     style="float:left; overflow: hidden; width: 100px; height: 80px;"
                     alt="no image">
            @endif


        <center>
            <ul class="list-style-none" style="width: 400px; margin: 0 auto; text-align: center;">
                <li style="font-size: 17px !important; font-weight: bold;">{{ factoryName() }}</li>
                <li style="font-size: 13px !important">{{ factoryAddress() }}</li>
                <li>
                    <div class="appointment-title" style="">নিয়োগপত্র (Appointment Letter)</div>
                </li>
            </ul>
        </center>
    </div>

    <div class="employee-details" style="">
        <table class="table-style" style="width: 100%; text-align: left;">
            <tr>
                <th style="width: 13%;">নাম</th>
                <th style="width: 2%;">:</th>
                <td style="width: 20%;">{{ $employee->name_bn }}</td>
                <th style="width: 13%;">কার্ড নং</th>
                <th style="width: 2%;">:</th>
                <td style="width: 24% !important;">{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($employee->officialInfo->unique_id) }}</td>
            </tr>

            <tr>
                <th>পিতার নাম</th>
                <th>:</th>
                <td>{{ $employee->father_name_bn }}</td>
                <th>বিভাগ</th>
                <th>:</th>
                <td>{{ $employee->officialInfo->departmentDetails->name_bn }}</td>
            </tr>

            <tr>
                <th>মাতার নাম</th>
                <th>:</th>
                <td>{{ $employee->mother_name_bn }}</td>
                <th>গ্রেড</th>
                <th>:</th>
                <td>{{ $employee->employeeOfficialInfo->grade->name_bn }}</td>
            </tr>

            <tr>
                <th>স্বামী বা স্ত্রীর নাম</th>
                <th>:</th>
                <td></td>
                <th>পদবী</th>
                <th>:</th>
                <td>{{ $employee->employeeOfficialInfo->designationDetails->name }}</td>
            </tr>

            <tr>
                <th>শ্রমিকের শ্রেণী</th>
                <th>:</th>
                <td>{{ Str::ucfirst($employee->employeeOfficialInfo->type ) }}</td>
                <th>যোগদানের তারিখ</th>
                <th>:</th>
                <td style="width: 10%;">{{ $employee->employeeOfficialInfo->date_of_joining_bn ?? date('d-m-Y', strtotime($employee->employeeOfficialInfo->date_of_joining)) }}</td>
                <th style="width: 10%;">কাজের ধরন</th>
                <th style="width: 2%;">:</th>
                <td>{{ $employee->employeeOfficialInfo->workType->name_bn }}</td>
            </tr>
        </table>

    </div>

    <div class="address">
        <table class="" style="width: 100%; text-align: left;">
            <tbody>
            <tr>
                <th style="width: 10%; text-decoration: underline;"><u>স্থায়ী ঠিকানা</u></th>
                <th style="width: 2%;">:</th>
                <td style="width: 25%;">{{ $employee->permanent_address_bn }}</td>
                <th style="width: 10%; text-decoration: underline;"><u>বর্তমান ঠিকানা</u></th>
                <th style="width: 2%;">:</th>
                <td style="">{{ $employee->present_address_bn }}</td>
            </tr>

            <tr>
                <th>গ্রাম</th>
                <th>:</th>
                <td>{{ $employee->village_bn }}</td>
                <th>গ্রাম</th>
                <th>:</th>
                <td>{{ $employee->present_address_village_bn }}</td>
            </tr>

            <tr>
                <th>ডাকঘর</th>
                <th>:</th>
                <td>{{ $employee->post_office_bn }}</td>
                <th>ডাকঘর</th>
                <th>:</th>
                <td>{{ $employee->present_address_post_office_bn }}</td>
            </tr>

            <tr>
                <th>থানা</th>
                <th>:</th>
                <td>{{ $employee->upazilla->name ?? $employee->upazilla->name_bn }}</td>
                <th>থানা</th>
                <th>:</th>
                <td>{{ $employee->presentAdressUpazilla->name ?? $employee->presentAdressUpazilla->name_bn }}</td>
            </tr>

            <tr>
                <th>জেলা</th>
                <th>:</th>
                <td>{{ $employee->zilla->name ?? $employee->zilla->name_bn }}</td>
                <th>জেলা</th>
                <th>:</th>
                <td>{{ $employee->presentAdressZilla->name ?? $employee->presentAdressZilla->name_bn }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="employeement-rules" style="margin-top: 5px;">
        <center style="font-weight: bold !important;">
            <strong style=""><u>প্রার্থীর চাকুরীর শর্তাবলী ও নিয়ামাবলী</u></strong>
        </center>

        <strong>১। বেতন ও ভাতা : </strong>
        <table style="border-collapse: collapse;">
            <tbody>
            <tr>
                <td style="width: 33%;">ক) মূল বেতন (Monthly Basic Salary)</td>
                <th style="width: 2%;">:</th>
                <th style="width: 6%;">টাকা</th>
                <td style="width: 6%;">{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn(number_format($employee->salary->basic)) }}</td>
                <th style="width: 5%;">/=</th>
                <th style="width: 5%;">(Tk)</th>
                <td style="width: 6%;">{{ $employee->salary->basic }}</td>
                <th>/=</th>
                <td style="width: 18%;"></td>
                <td style="width: 18%;"></td>
            </tr>
            <tr>
                <td>খ) বাড়ী ভাড়া (House Rent)</td>
                <th>:</th>
                <th>টাকা</th>
                <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn(number_format($employee->salary->house_rent)) }}</td>
                <th>/=</th>
                <th>(Tk)</th>
                <td>{{ $employee->salary->house_rent }}</td>
                <th>/=</th>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>গ) চিকিৎসা ভাতা (Medical Allowance)</td>
                <th>:</th>
                <th>টাকা</th>
                <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn(number_format($employee->salary->medical)) }}</td>
                <th>/=</th>
                <th>(Tk)</th>
                <td>{{ $employee->salary->medical }}</td>
                <th>/=</th>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>ঘ) যাতায়াত ভাতা (Conveyance Allowance)</td>
                <th>:</th>
                <th>টাকা</th>
                <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn(number_format($employee->salary->transport)) }}</td>
                <th>/=</th>
                <th>(Tk)</th>
                <td>{{ $employee->salary->transport }}</td>
                <th>/=</th>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>ঙ) খাদ্য ভাতা (Food Allowance)</td>
                <th>:</th>
                <th>টাকা</th>
                <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn(number_format($employee->salary->food)) }}</td>
                <th>/=</th>
                <th>(Tk)</th>
                <td>{{ $employee->salary->food }}</td>
                <th>/=</th>
                <td class="border" style="width: 15%; border: 1px solid #000;font-weight: bold; padding-left: 3px;">প্রতি ঘণ্টা ওভার টাইম
                </td>
                <td class="border" style="width: 12%; border: 1px solid #000;font-weight: bold; padding-left: 3px;">OT Rate Per Hour</td>
            </tr>
            <tr class="outer-border">
                <td>মোট বেতন (Monthly Gross Salary)</td>
                <th>:</th>
                <th>টাকা</th>
                <td>{{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn(number_format($employee->salary->gross)) }}</td>
                <th>/=</th>
                <th>(TK)</th>
                <td>{{ $employee->salary->gross }}</td>
                <th>/=</th>
                @php
                    $otAmount = round(($employee->salary->basic / 208) * (2 * 1), 2);
                @endphp
                <td class="border" style="border: 1px solid #000;font-weight: bold; padding-left: 3px;">
                    টাকা: {{ \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($otAmount) }}</td>
                <td class="border" style="border: 1px solid #000;font-weight: bold; padding-left: 3px">TK: {{ $otAmount }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="income-rules" style="">
        <strong>০২। অন্যান্য প্রদেয় আর্থিক সুবিধাঃ </strong>
        <ul class="list-style-none">

            <li>
                <span class="">ক) উৎসব ভাতাঃ নিরবিচ্ছিন্নভাবে চাকুরী এক (০১) বছর  পূর্ণ হইলে ঈদে মূল মজুরীর পূর্ণ হারে  উৎসব ভাতা প্রদান করা হয়।  </span>
            </li>
            <li>
                <span
                    class="">খ) হাজিরা ভাতাঃ আপনি বর্তমান পদবীতে হাজিরা ভাতা &nbsp; <b> {{ $employee->employeeOfficialInfo->salary->attendance_bonus ?? 0 }} </b> &nbsp; /= টাকা পাইবেন । </span>
            </li>
            <li>
                <span class="">খ) বাৎসরিক মজুরী বৃদ্ধিঃ মূল মজুরীর নুন্যতম ৫% হারে বাৎসরিক মজুরী বৃদ্ধি পাইবে ।</span>
            </li>

        </ul>
    </div>

    <div class="working-rules" style="">
        <strong>০৩। কর্মঘন্টা এবং ওভারটাইমঃ </strong>
        <ul class="list-style-none">

            <li>
                <span class="">ক) দৈনিক কর্ম ঘন্টাঃ ০৮ (আট) ঘন্টা।  </span>
            </li>
            <li>
                <span class="">খ) বিরতিঃ প্রতি কর্ম দিবসে / শিফটে ০১ (এক) ঘন্টা।</span>
            </li>
            <li>
                <span class="">গ) দৈনিক ওভারটাইমঃ শ্রমিকের সম্মতিক্রমে সর্বোচ্চ ০২ (দুই) ঘন্টা। দৈনিক ০৮ (আট) ঘন্টার বেশী কাজ ওভারটাইম হিসেবে গণ্য হবে।</span>
            </li>
            <li>
                <span class="">ঘ) ওভারটাইম হিসাবঃ মূলবেতনের দ্বিগুন হারে হিসাব করা হয়। হিসাবঃ <b>মূল বেতন / ২০৮ x ২ x মোট ওভারটাইম ঘন্টা</b></span>
            </li>
            <li>
                <span class="">ঙ) বেতন প্রদানের সময়ঃ মাসের প্রথম (০৭) কর্মদিবসের মধ্যে বেতন ও ওভারটাইম দেয়া হয়।</span>
            </li>


        </ul>
    </div>

    <div class="holiday-rules" style="">
        <strong>০৪। সাধারণ ছুটিঃ </strong>
        <ul class="list-style-none">

            <li>
                <span class="">ক) সাপ্তাহিক ছুটিঃ  সপ্তাহে এক (০১) দিন (সাধারণত শুক্রবার)।</span>
            </li>
            <li>
                <span class="">খ) উৎসবজনিত ছুটিঃ বছরে এগারো (১১) দিন (পূর্ণ বেতনে)।</span>
            </li>
            <li>
                <span class="">গ) নৈমিত্তিক ছুটিঃ বছরে দশ (১০) দিন (পূর্ণ বেতনে)।</span>
            </li>
            <li>
                <span class="">ঘ) পীড়া ছুটিঃ বছরে চৌদ্দ (১৪) দিন (পূর্ণ বেতনে)।</span>
            </li>
            <li>
                <span class="">ঙ) বাৎসরিক ছুটিঃ এক বছর চাকুরী সম্পন্ন করার পর প্রতি আঠারো (১৮) কর্মদিবসের বিপরীতে এক (০১) দিন যা সর্বোচ্চ ৪০ দিন পর্যন্ত জমা রাখা যাবে।</span>
            </li>
            <li>
                <span class="">চ) প্রসূতি ছুটিঃ সন্তান প্রসবের পূর্বে আট (০৮) সপ্তাহ এবং পরে আট (০৮) সপ্তাহ (পূর্ণ বেতনে) (**অন্যান্য শর্ত শ্রম আইন অনুযায়ী প্রযোজ্য হবে)</span>
            </li>


        </ul>
    </div>

    <div class="rules-details">
        <strong>৫। শর্তাবলীঃ </strong>
        <ul class="list-style-none">

            <li><span style="">
                    ক) শিক্ষানবিস কাল (প্রবেশন পিরিয়ড):- আপনাকে আপাতত শিক্ষানবিস হিসাবে নিয়োগ করা হইল। আপনার শিক্ষানবিসকাল
                হবে তিন (০৩) মাস।
                তবে শর্ত থাকে যে, একজন দক্ষ শ্রমিকের ক্ষেত্রে তাহাড় শিক্ষানবিসকাল আরও তিন (০৩) মাস বৃদ্ধি করা যাবে যদি
                কোন কারণে প্রথম তিন (০৩) মাস
                শিক্ষানবিসকাল তাহার কাজের মান নির্নয় করা সম্ভব না হয়। কৃতিত্বের সহিত শিক্ষানবিসকাল সমাপ্তির পর আপনি এই
                প্রতিষ্ঠানের একজন স্থায়ী শ্রমিক হিসেবে
                বিবেচিত হবেন। শিক্ষানবিসকাল বা তিন (০৩) মাস মেয়াদ বৃধি শেষে কোন চিঠি প্রদান করা না হইলে আপনি স্থায়ী
                শ্রমিক হিসেবে বিবেচিত হবেন।
                </span>
            </li>
            <li>
                <span>
                    খ) চাকুরী স্থায়ী হবার পর আপনি স্বেচ্ছায় চাকুরী ছাড়িয়া দিতে চাইলে ষাট (৬০) দিনের লিখিত নোটিশ অথবা নোটিশ
                মেয়াদের বিপরিতে ষাট (৬০) দিনের
                সমপরিমানের মজুরী কোম্পানিকে প্রদান করিয়া চাকুরী হইতে ইস্তফা দিতে পারবেন। অপর পক্ষে বরখাস্ত ইত্যাদি
                ব্যতীত অন্যভাবে মালিক কর্তৃক আপনার চাকুরীর
                অবসানের ক্ষেত্রে মালিক আপনাকে একশত বিশ (১২০) দিনের লিখিত নোটিশ অথবা মেয়াদের জন্য মজুরী প্রদানের সাপেক্ষে
                আপনার চাকুরীর অবসান ঘটাইতে
                পারবে।
                </span>
            </li>
            <li>
                <span>
                    গ) আপনার চাকুরী কোম্পানী কর্তৃক জারীকৃত বিধি-বিধান ও বিদ্যমান শ্রম আইন অনুযায়ী পরিচালিত হবে। কোম্পানীর
                যাবতীয় নিয়ম-কানুন পরিবর্তন সাপেক্ষে
                এবং আপনি পরিবর্তিত নিয়ম-কানুন সর্বদা মানিয়া চলিতে বাধ্য থাকবেন। আপনি যদি কখনো কোনরূপ অসদাচরণের অপরাধে
                দোষী প্রমানিত হন, তবে কর্তৃপক্ষ
                আইন মোতাবেক আপনাকে চাকুরীচ্যুত সহ আইনানুগ যে কোন ধরনের শাস্তি প্রদান করতে পারবেন। এক্ষেত্রে বাংলাদেশ
                শ্রম আইন ২০০৬ এবং বাংলাদেশ শ্রম
                বিধিমালা ২০১৫ অনুসরন করা হইবে।
                </span>
            </li>
            <li>
                <span>
                    ঘ) কর্তৃপক্ষ প্রয়োজনবোধে আপনাকে এই প্রতিষ্ঠানের যে কোন বিভাগ বা এই গ্রুপের যে কোন কারখানায় / অফিসে বদলী
                করতে পারবেন।
                </span>
            </li>
            <li>
                <span>
                    ঙ) অত্র প্রতিষ্ঠানে কর্মরত থাকাকালীন সময় আপনি অন্য কোথাও প্রত্যক্ষ বা পরোক্ষভাবে কোন চাকুরী গ্রহন করিতে
                পারবেন না।
                আপনার চাকুরীর পরিসমাপ্তি ঘটলে আপনি এই কোম্পানির সমস্ত কাগজপত্র, দলিলাদি অথবা অন্যকোন বস্তু আপনার হেফাজতে
                থাকলে সেই সকল দ্রব্যাদি
                আপনি ফেরত দিবেন এবং কোম্পানির ব্যবসা সংক্রান্ত কোন কাগজ পত্রের নকল অথবা অংশ বিশেষ আপনার নিকট রাখতে
                পারবেন না। আপনি নির্দিষ্ট দায়িত্ব
                পালনকালে বা চাকুরী পরিবর্তনের ক্ষেত্রে প্রতিষ্ঠানের ব্যবসায়িক কৌশলের গোপনীয়তা সংরক্ষণ করবেন। নিয়োগের
                যাবতীয় শর্ত বিদ্যমান শ্রম আইন অনুযায়ী
                পরিচালিত হবে।

                </span>
            </li>
            <li>
                <span>
                    চ) উপরোল্লিখিত শর্তাবলী গ্রহনযোগ্য হলে
                &nbsp; {{ $employee->employeeOfficialInfo->date_of_joining_bn ?? date('d-m-Y', strtotime($employee->employeeOfficialInfo->date_of_joining)) }}
                &nbsp; তারিখ বা তার পূর্বে কর্মে যোগদান করতে পারবেন। <br>
                আমি উপরোক্ত শর্তাদি নিজে পড়ে, বুঝে সুস্থ ও স্বাভাবিক মস্তিষ্কে কারো দ্বারা প্ররোচিত এবং কারো হুমকির মুখে
                না পরে স্বেচ্ছায় নিম্নে স্বাক্ষর করলাম।

                </span>

            </li>
        </ul>
    </div>

    <div class="footer-text">
        <table style="width: 100%; margin-top: 10px;">
            <tbody>
            <tr>
                <td>__________________</td>
                <td></td>
                <td></td>
                <td></td>
                <td>__________________</td>
            </tr>
            <tr>
                <td style="width: 40%;">গ্রহণকারীর স্বাক্ষর</td>
                <td style="width: 3%;">তারিখঃ</td>
                <td style="width: 8%;">{{ $employee->employeeOfficialInfo->date_of_joining_bn ?? date('d-m-Y', strtotime($employee->employeeOfficialInfo->date_of_joining)) }}</td>
                <td>ইং</td>
                <td style="width: 25%;">নিয়োগকর্তার স্বাক্ষর</td>
            </tr>
            </tbody>
        </table>
    </div>

</div>
