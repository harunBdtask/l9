<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;


use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrGrade;
use SkylarkSoft\GoRMG\HR\Requests\GradeRequest;

class GradeRepository
{
    public function all()
    {
        return HrGrade::orderBy('name', 'asc')->get();
    }

    public function paginate()
    {
        return HrGrade::orderBy('name', 'asc')->paginate();
    }

    public function store(GradeRequest $request)
    {
        try {
            $id = $request->id ?? '';
            $grade = HrGrade::findOrNew($id);
            $grade->fill($request->all());
            $grade->save();
            return $grade;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function show($id)
    {
        try {
            return HrGrade::query()->with('group')->find($id);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function update(GradeRequest $request)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        try {
            $grade = HrGrade::find($id);
            $grade->delete();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
