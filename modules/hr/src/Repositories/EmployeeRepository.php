<?php


namespace SkylarkSoft\GoRMG\HR\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SkylarkSoft\GoRMG\HR\Models\HrEmployee;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Requests\EmployeeRequest;

class EmployeeRepository
{
    public function all()
    {
        return HrEmployee::with('salary', 'officialInfo.grade')->get();
    }

    public function fetchForSelect()
    {
        return Cache::remember('employees-for-select', 60, function () {
            return HrEmployee::all(['id', 'first_name', 'last_name', 'unique_id']);
        });
    }

    public function dailyRoastingEmployees()
    {
        return HrEmployee::with('employeeOfficialInfo.departmentDetails', 'employeeOfficialInfo.designationDetails')
            ->whereHas('employeeOfficialInfo.designationDetails', function ($query) {
                $query->where('name', 'Security Guard');
            })->whereHas('employeeOfficialInfo.departmentDetails', function ($query) {
                $query->where('name', 'Personnel');
            })->get();
    }

    public function paginate($type = HrEmployee::WORKER)
    {
        return HrEmployee::with('salary', 'officialInfo.grade')
            ->whereHas('officialInfo', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function paginateStaff()
    {
        return $this->paginate(HrEmployee::STAFF);
    }

    public function staffList()
    {
        return HrEmployee::with('salary', 'officialInfo.grade')
            ->whereHas('officialInfo', function ($query) {
                return $query->where('type', 'staff');
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function workerList()
    {
        return HrEmployee::with('salary', 'officialInfo.grade')
            ->whereHas('officialInfo', function ($query) {
                return $query->where('type', 'worker');
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function search(Request $request, $type = HrEmployee::WORKER)
    {
        $search_key = $request->key;
        $search_value = $request->value;
        $query = HrEmployeeOfficialInfo::query();

        if ($search_key == 'code') {
            $query->where('code', 'like', "%$search_value%");
        } else {
            if ($search_key == 'unique_id') {
                $query->where('unique_id', 'like', "%$search_value%");
            } else {
                if ($search_key == 'department') {
                    $query->whereHas('departmentDetails', function ($child_query) use ($search_value) {
                        $child_query->where('name', 'like', "%$search_value%")
                            ->orWhere('name_bn', 'like', "%$search_value%");
                    });
                } else {
                    if ($search_key == 'designation') {
                        $query->whereHas('designationDetails', function ($child_query) use ($search_value) {
                            $child_query->where('name', 'like', "%$search_value%")
                                ->orWhere('name_bn', 'like', "%$search_value%");
                        });
                    } else {
                        if ($search_key == 'section') {
                            $query->whereHas('sectionDetails', function ($child_query) use ($search_value) {
                                $child_query->where('name', 'like', "%$search_value%")
                                    ->orWhere('name_bn', 'like', "%$search_value%");
                            });
                        }
                    }
                }
            }
        }

        $employeeOfficialInfos = $query->pluck('employee_id');

        $query = HrEmployee::whereIn('id', $employeeOfficialInfos)
            ->with('officialInfo.grade')
            ->orderBy('unique_id', 'asc');

        if ($type) {
            $query->whereHas('officialInfo', function ($q) use ($type) {
                $q->where('type', $type);
            });
        }

        return $query->paginate();
    }

    public function listSearch(Request $request)
    {
        return $this->search($request, null);
    }

    public function searchStaffs(Request $request)
    {
        return $this->search($request, HrEmployee::STAFF);
    }

    public function store(EmployeeRequest $request)
    {
        try {
            $id = $request->id ?? '';
            $files = '';
            if ($request->hasFile('photo')) {
                $time = time();
                $file = $request->photo;
                $file->storeAs('employee_photo', $time . $file->getClientOriginalName());
                $files = $time . $file->getClientOriginalName();
            }
            $employee = HrEmployee::findOrNew($id);
            $employee->fill(array_merge($request->except(['department', 'section', 'designation', 'code', 'type']), [
                'photo' => $files,
                'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
            ]));

            $employee->nominee_relation_bn = $this->nomineeTranslation($request->nominee_relation);
            $employee->religion_bn = $this->relagionsTranslation($request->religion);
            $employee->save();
            return $employee;
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            return false;
        }

    }

    private function nomineeTranslation($key)
    {
        $relations = [
            'father' => 'বাবা',
            'mother' => 'মা',
            'wife' => 'বউ',
            'husband' => 'স্বামী',
            'sister' => 'বোন',
            'brother' => 'ভাই',
            'son' => 'ছেলে',
            'daughter' => 'মেয়ে'
        ];
        if (array_key_exists($key, $relations)) {
            return $relations[$key];
        }
        return null;
    }

    private function relagionsTranslation($key)
    {
        $religions = [
            'islam' => 'ইসলাম',
            'hinduism' => 'হিন্দু',
            'buddhism' => 'বৌদ্ধ',
            'christianity' => 'খ্রিষ্টান',
            'others' => 'অন্যান্য'
        ];
        if (array_key_exists($key, $religions)) {
            return $religions[$key];
        }
        return null;
    }

    public function show($id)
    {
        try {
            return HrEmployee::find($id);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function update(EmployeeRequest $request)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        try {
            $employee = HrEmployee::find($id);
            $employee->document()->delete();
            $employee->salary()->delete();
            $employee->jobExperiences()->delete();
            $employee->educations()->delete();
            $employee->employeeOfficialInfo()->delete();
            $employee->delete();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
