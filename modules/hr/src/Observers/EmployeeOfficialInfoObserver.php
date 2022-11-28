<?php

namespace SkylarkSoft\GoRMG\HR\Observers;


use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;

class EmployeeOfficialInfoObserver
{
    public function created(HrEmployeeOfficialInfo $employeeOfficialInfo)
    {
        HrEmployee::find($employeeOfficialInfo->employee_id)->update(['unique_id'=> $employeeOfficialInfo->unique_id]);
    }

    public function updated(HrEmployeeOfficialInfo $employeeOfficialInfo)
    {
        error_log(print_r($employeeOfficialInfo, true), 3, 'sdsds.log');
        HrEmployee::find($employeeOfficialInfo->employee_id)->update(['unique_id'=> $employeeOfficialInfo->unique_id]);
    }

    public function deleted(HrEmployeeOfficialInfo $employeeOfficialInfo)
    {
        //
    }

    public function restored(HrEmployeeOfficialInfo $employeeOfficialInfo)
    {
        //
    }

    public function forceDeleted(HrEmployeeOfficialInfo $employeeOfficialInfo)
    {
        //
    }
}
