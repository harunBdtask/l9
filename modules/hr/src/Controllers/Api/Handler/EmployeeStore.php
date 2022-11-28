<?php


namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Handler;

use SkylarkSoft\GoRMG\HR\Models\HrEmployee;

class EmployeeStore
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function store()
    {
        try {
            $id = $this->request->id ?? '';
            $files = '';
            if ($this->request->hasFile('photo')) {
                $time = time();
                $file = $this->request->photo;
                $file->storeAs('employee_photo', $time . $file->getClientOriginalName());
                $files = $time . $file->getClientOriginalName();
            }
            $employee = HrEmployee::findOrNew($id);
            $employee->fill(array_merge($this->request->all(), ['photo' => $files, 'date_of_birth' => date('Y-m-d', strtotime($this->request->date_of_birth))]));
            $employee->save();

            return [
                'success' => true,
                'errors' => null,
                'message' => 'Data Stored Successful!',
            ];
        } catch (\Exception $exception) {

            return [
                'status' => false,
                'errors' => $exception->getMessage(),
                'message' => 'Data Stored Failed!',
            ];
        }
    }
}
