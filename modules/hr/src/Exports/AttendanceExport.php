<?php

namespace SkylarkSoft\GoRMG\HR\Exports;

use SkylarkSoft\GoRMG\HR\Models\HrAttendanceSummary;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithMapping, WithHeadings
{
    public function headings(): array
    {
        return [
            'ID',
            'Unique ID',
            'Punch Card ID',
            'First Name',
            'Last Name',
            'Department',
            'Section',
            'Designation',
            'Type',
            'Attendance Date',
            'Punch Time',
            'Status',
        ];
    }

    public function collection()
    {
        return HrAttendanceSummary::with([
            'employeeOfficialInfo.departmentDetails',
            'employeeOfficialInfo.designationDetails',
            'employeeOfficialInfo.sectionDetails',
        ])->get();
    }

    public function map($attedance): array
    {
        return [
            'ID' => $attedance->id,
            'Unique ID' => $attedance->employeeOfficialInfo->unique_id,
            'Punch Card ID' => $attedance->employeeOfficialInfo->punch_card_id,
            'First Name' => $attedance->employeeOfficialInfo->employeeBasicInfo->first_name,
            'Last Name' => $attedance->employeeOfficialInfo->employeeBasicInfo->last_name,
            'Department' => $attedance->employeeOfficialInfo->departmentDetails->name,
            'Section' => $attedance->employeeOfficialInfo->sectionDetails->name,
            'Designation' => $attedance->employeeOfficialInfo->designationDetails->name,
            'Type' => $attedance->employeeOfficialInfo->type,
            'Attendance Date' => $attedance->date,
            'Punch Time' => $attedance->employeeOfficialInfo->attendanceRawData->punch_time,
            'Status' => $attedance->status,
        ];
    }

}
