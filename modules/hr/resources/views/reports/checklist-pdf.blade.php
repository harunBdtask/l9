<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport"
         content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Document</title>
   <style>
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
         border: 1px solid #ccc;
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
         height: 50px;
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

      .spacer {
         height: .5rem;
      }

      tr {
         page-break-inside: avoid;
      }
   </style>
</head>
<body>

<div>
   <h3 class="text-center">Employee Check List</h3>
   <h1>Hello Hello</h1>
   <table class="reportTable" style="border-collapse: collapse">
      <tr>
         <th rowspan="2">SL</th>
         <th>Name</th>
         <th>Card</th>
         <th rowspan="2">Unique ID</th>
         <th rowspan="2">Gross Salary</th>
         <th rowspan="2">Joining Date</th>
         <th rowspan="2">Birth Date</th>
         <th>Name Bangla</th>
         <th>Father Bangla</th>
         <th rowspan="2">Permanent Address</th>
         <th rowspan="2">Entry Date</th>
      </tr>
      <tr>
         <th>Designation</th>
         <th>Grade</th>
         <th>Designation Bangla</th>
         <th>Mother Bangla</th>
      </tr>

      @foreach($employees as $employee)
         <tr>
            <td rowspan="2">{{ $loop->index + 1 }}</td>
            <td>{{ $employee->first_name . ' ' . $employee->last_name }}</td>
            <td>{{ $employee->code }}</td>
            <td rowspan="2">{{ optional($employee->officialInfo)->unique_id ?? 'N/A' }}</td>
            <td rowspan="2">{{ optional($employee->salary)->unique_id ?? 'N/A' }}</td>
            <td rowspan="2">{{ optional($employee->officialInfo)->date_of_joining ? \Carbon\Carbon::parse(optional($employee->officialInfo)->date_of_joining)->format('d-M-y') : 'N/A' }}</td>
            <td rowspan="2">{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d-M-y') }}</td>
            <td>{{ $employee->name_bn ?? 'N/A' }}</td>
            <td>{{ $employee->father_name_bn ?? 'N/A' }}</td>
            <td rowspan="2" style="overflow-wrap: break-word;">{{ $employee->permanent_address_bn ?? 'N/A'  }}</td>
            <td rowspan="2">{{ \Carbon\Carbon::parse($employee->created_at)->format('d-M-y') }}</td>
         </tr>
         <tr>
            <td>{{ optional($employee->officialInfo->designationDetails)->name ?? 'N/A' }}</td>
            <td>{{ optional($employee->officialInfo)->grade_id ?? 'N/A' }}</td>
            <td>{{ $employee->officialInfo->designationDetails->name_bn ?? 'N/A' }}</td>
            <td>{{ $employee->mother_name_bn ?? 'N/A' }}</td>
         </tr>
      @endforeach
   </table>
</div>

</body>
</html>
