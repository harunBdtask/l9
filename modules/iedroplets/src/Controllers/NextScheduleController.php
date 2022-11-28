<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Iedroplets\Models\NextSchedule;
use SkylarkSoft\GoRMG\Iedroplets\Requests\NextScheduleRequest;
use Carbon\Carbon;
use DB, Session;

class NextScheduleController extends Controller
{

    public function nextSchedule()
    {
        $floors = Floor::pluck('floor_no', 'id')->all();
        $buyers = Buyer::pluck('name', 'id')->all();

        return view('iedroplets::forms.next_schedule', [
        	'next_schedule' => null,
            'floors' => $floors,
            'buyers' => $buyers
        ]);
    }

    public  function getLineWiseNextSchedule($floor_id)
    {
        $lines = Line::with('inspectionSchedule')->where('floor_id', $floor_id)->get();
        $buyers = Buyer::pluck('name', 'id')->all();
        $view = view('iedroplets::forms.next_schedule_form', compact('lines', 'buyers'))->render();
        return $view;
    }

    public function postLineWiseNextSchedule(Request $request)
    {
        $result = [];
        $nextScheduleId = null;
        
        try {
            $nextSchedule = NextSchedule::findOrNew($request->id);
            $nextSchedule->floor_id = $request->floor_id;
            $nextSchedule->line_id = $request->line_id;
            $nextSchedule->buyer_id = $request->buyer_id;
            $nextSchedule->order_id = $request->order_id;
            $nextSchedule->output_finish_date = $request->output_finish_date;
            $nextSchedule->next_schedule_date =  Carbon::tomorrow()->format('Y-m-d');
            $nextSchedule->created_by = userId();
            $nextSchedule->updated_by = userId();
            $nextSchedule->save();
            
            $nextScheduleId = $nextSchedule->id;
            $status = 200;
        } catch (Exception $e) { 
            $status = 500;
        }
 
        $result['nextScheduleId'] = $nextScheduleId;
        $result['status'] = $status;

        return $result;
    }

}
