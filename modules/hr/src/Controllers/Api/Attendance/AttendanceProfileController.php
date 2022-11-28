<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AttendanceProfileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'userid'    => 'required',
            'date_from' => 'required',
            'date_to'   => 'required'
        ]);


        $query = DB::table('hr_attendance_summaries')
            ->where('userid', $request->userid)
            ->where('date', '>=', $request->date_from)
            ->where('date', '<=', $request->date_to);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $attendances = $query->get();

        try {
            $view = view('hr::attendance.profile', compact('attendances'))->render();
        } catch (Throwable $e) {
            $view = '<h1>Something Went Wrong!</h1>';
        }

        return response(['view' => $view], Response::HTTP_OK);
    }
}
