<?php

namespace SkylarkSoft\GoRMG\HR\Repositories;

use SkylarkSoft\GoRMG\HR\Models\HrFastivalLeave;

class FestivalLeaveRepository
{
    public function all()
    {
        return HrFastivalLeave::orderBy('leave_date', 'desc')->get();
    }

    public function paginate()
    {
        return HrFastivalLeave::orderBy('leave_date', 'desc')->paginate();
    }

    public function store($request)
    {
        try {
            $id = $request->id ?? '';
            $festival_leave = HrFastivalLeave::findOrNew($id);
            $festival_leave->fill($request->all());
            $festival_leave->save();
            return $festival_leave;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function show($id)
    {
        try {
            return HrFastivalLeave::find($id);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function update($request)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        try {
            $festival_leave = HrFastivalLeave::find($id);
            $festival_leave->delete();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
