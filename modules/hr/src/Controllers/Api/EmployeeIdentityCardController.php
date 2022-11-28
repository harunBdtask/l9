<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;


class EmployeeIdentityCardController extends Controller
{
    public function generateIdentityCard()
    {
        $designation_id = \request()->designation_id ?? '';
        $department_id = \request()->department_id ?? '';
        $section_id = \request()->section_id ?? '';
        $unique_id = \request()->unique_id ?? '';
        $front_back = \request()->front_back;
        $from_date = \request()->from_date ?? '';
        $to_date = \request()->to_date ?? '';
        $unique_ids = json_decode($unique_id);
        $employees = HrEmployee::with('departmentDetails', 'designationDetails', 'officialInfo', 'salary', 'document'
                                , 'termination')
            ->when(count($unique_ids) > 0, function ($query) use ($unique_ids) {
                return $query->whereIn('unique_id', $unique_ids);
            })
            ->when($designation_id, function ($query) use ($designation_id) {
                return $query->wherehas('officialInfo', function ($query) use ($designation_id) {
                    return $query->where('designation_id', $designation_id);
                });
            })
            ->when($department_id, function ($query) use ($department_id) {
                return $query->wherehas('officialInfo', function ($query) use ($department_id) {
                    return $query->where('department_id', $department_id);
                });
            })
            ->when($section_id, function ($query) use ($section_id) {
                return $query->wherehas('officialInfo', function ($query) use ($section_id) {
                    return $query->where('section_id', $section_id);
                });
            })
            ->when($from_date && $to_date, function($query) use ($from_date, $to_date) {
                return $query->whereDate('created_at', '>=', $from_date)
                    ->whereDate('created_at', '<=', $to_date);
            })
            ->whereDoesntHave('termination')
            ->get();

        if ($front_back == 1) {
            return view('hr::reports.employee_identity_card_generation_front', ['employees' => $employees]);
        } elseif ($front_back == 2) {
            return view('hr::reports.employee_identity_card_generation_back', ['employees' => $employees]);
        } else {
            return view('hr::reports.employee_identity_card_generation', ['employees' => $employees]);
        }
    }
}
