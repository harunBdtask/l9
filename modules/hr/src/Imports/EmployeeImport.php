<?php

namespace SkylarkSoft\GoRMG\HR\Imports;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use SkylarkSoft\GoRMG\HR\Models\HrDepartment;
use SkylarkSoft\GoRMG\HR\Models\HrDesignation;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrGrade;
use SkylarkSoft\GoRMG\HR\Models\HrSection;
use Throwable;

class EmployeeImport implements ToCollection, WithHeadingRow, WithMultipleSheets, WithEvents
{
    use Importable, RegistersEventListeners;

    protected function validation($row): bool
    {
        $row = collect($row)->toArray();
        $required = [
            'id',
            'name',
            'nid',
            'gender',
            'department',
            'designation'
        ];
        return collect($required)->every(function ($field) use ($row) {
            return (array_key_exists($field, $row) && $row[$field] !== null);
        });
    }

    /**
     * @param Collection $collection
     * @throws Throwable
     */
    public function collection(Collection $collection)
    {
        DB::beginTransaction();
        foreach ($collection as $row) {
            $exist = HrEmployee::query()->where('unique_id', $row['id'])->exists();
            if ($exist || !$this->validation($row)) {
                continue;
            }
            $employeeData = $this->hrEmployeeData($row);
            /* Save in Employee Basic Info */
            $employee = HrEmployee::query()->create($employeeData);

            $employeeOfficialInfo = $this->hrEmployeeOfficialInfoData($row, $employee);

            /* Save in Employee Official Info */
            HrEmployeeOfficialInfo::query()->create($employeeOfficialInfo);
        }
        DB::commit();
    }

    private function hrEmployeeData($row): array
    {
        $unique_id = trim($row['id']);
        $gender = (($row['gender'] == 'M' || $row['gender'] == 'Male') ? 'Male' : 'Female') ?? ucfirst(trim($row['gender'] ));
        $name = trim($row['name']);
        $nameArray = $this->getFirstNameLastNameAttribute($name, $gender);
        $nid = (string)trim($row['nid']) ?? 00000;
        $father_name = trim($row['father_name']) ?? '';
        $date_of_birth = DateTime::createFromFormat('d/m/Y', $row['date_of_birth']);
        $date_of_birth = $date_of_birth->format('Y-m-d');

        return [
            'unique_id' => $unique_id,
            'first_name' => $nameArray['first_name'],
            'last_name' => $nameArray['last_name'],
            'father_name' => $father_name,
            'nid' => $nid,
            'sex' => $gender,
            'date_of_birth' => $date_of_birth
        ];
    }

    private function getFirstNameLastNameAttribute($name, $gender): array
    {
        $name_array = explode(' ', $name);
        $name_array_count = count($name_array);

        $first_name = '';
        if ($name_array_count > 1) {
            foreach ($name_array as $key => $value) {
                if ($key < $name_array_count - 1) {
                    $first_name .= $value . ' ';
                }
            }
        } else {
            $first_name = $gender == 'Male' ? 'Mr.' : 'Ms.';
        }

        $first_name = trim($first_name);
        $last_name = $name_array[$name_array_count - 1];
        return ['first_name' => $first_name, 'last_name' => $last_name];
    }

    private function hrEmployeeOfficialInfoData($row, $employee): array
    {
        $department_id = (int)trim($row['department']);
        $designation_id = (int)trim($row['designation']);
        $section_id = (int)trim($row['section']);
        $type = ucfirst(trim($row['type_of_employee'])) == 'Worker' ? 'worker' : 'staff';
        $date_of_joining = Carbon::instance(Date::excelToDateTimeObject($row['joining_date']))->toDateString();
        $group_id = (int)trim($row['group_id']);
        $grade_id = (int)trim($row['grade']);

        return [
            'employee_id' => $employee->id,
            'department_id' => $department_id,
            'designation_id' => $designation_id,
            'section_id' => $section_id,
            'group_id' => $group_id,
            'grade_id' => $grade_id,
            'type' => $type,
            'unique_id' => $employee->unique_id,
            'date_of_joining' => date('Y-m-d', strtotime($date_of_joining))
        ];
    }

    private function getDesignationId($designation)
    {
        $designation_model = HrDesignation::query()->firstOrCreate([
            'name' => $designation
        ], [
            'name_bn' => $designation
        ]);

        return $designation_model['id'];
    }

    private function getDepartmentId($department)
    {
        $department_model = HrDepartment::query()
            ->firstOrCreate([
                'name' => $department
            ], [
                'name_bn' => $department
            ]);
        return $department_model['id'];
    }

    private function getSectionId($section, $department)
    {
        $section_model = HrSection::query()
            ->firstOrCreate([
                'name' => $section
            ], [
                'name_bn' => $section,
                'department_id' => $this->getDepartmentId($department)
            ]);

        return $section_model['id'];
    }

    private function getGradeId($grade)
    {
        $grade_model = HrGrade::query()
            ->firstOrCreate([
                'name' => $grade
            ], [
                'name_bn' => $grade,
            ]);

        return $grade_model['id'];
    }


    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            // Select by sheet index
            0 => new EmployeeImport(),
        ];

    }

}
