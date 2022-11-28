<?php

namespace SkylarkSoft\GoRMG\HR\Resources;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NightOtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $date = $this->date;
        $approved_ot_start = $this->approved_night_start;
        $approved_ot_end = $this->approved_night_end;
        $user_night_start = $this->night_start;
        $user_night_end = $this->night_end;
        $night_ot_hour = $this->calculateNightOtHour($date, $user_night_start, $user_night_end, $approved_ot_start, $approved_ot_end);
        $approved_night_ot_from = $approved_ot_start ? Carbon::parse($this->date.''.$approved_ot_start)->format('h:i:s A') : '';
        $approved_night_ot_to = $approved_ot_end ? Carbon::parse($this->date.''.$approved_ot_end)->format('h:i:s A') : '';
        $approved_night_ot_from_to = ($approved_night_ot_from && $approved_night_ot_to) ? $approved_night_ot_from .' - '. $approved_night_ot_to : '';
        return [
            'id' => $this->id,
            'date' => $this->date ? date('d/m/Y', strtotime($this->date)) : '',
            'unique_id'=>$this->employeeOfficialInfo->unique_id,
            'screen_name' => $this->employeeOfficialInfo->employeeBasicInfo->screen_name,
            'first_name' => $this->employeeOfficialInfo->employeeBasicInfo->first_name,
            'last_name' => $this->employeeOfficialInfo->employeeBasicInfo->last_name,
            'type' => $this->employeeOfficialInfo->type,
            'department' => $this->employeeOfficialInfo->departmentDetails->name,
            'section' => $this->employeeOfficialInfo->sectionDetails->name,
            'designation' => $this->employeeOfficialInfo->designationDetails->name,
            'grade' => $this->employeeOfficialInfo->grade->name,
            'night_ot_from' => $user_night_start ? Carbon::parse($this->date.''.$user_night_start)->format('h:i:s A') : '',
            'night_ot_to' => $user_night_end ? Carbon::parse($this->date.''.$user_night_end)->format('h:i:s A') : '',
            'approved_night_ot_from' => $approved_night_ot_from,
            'approved_night_ot_to' => $approved_night_ot_to,
            'approved_night_ot_from_to' => $approved_night_ot_from_to,
            'night_ot_hour' => $night_ot_hour
        ];
    }

    /**
     * Calculate Night OT Hour
     *
     * @param $date
     * @param $night_start
     * @param $night_end
     * @param $approved_night_start
     * @param $approved_night_end
     * @return null|string
     */
    private function calculateNightOtHour($date, $night_start, $night_end, $approved_night_start, $approved_night_end)
    {
        $night_start_date_time = Carbon::parse($date.'T'.$night_start);
        $night_end_date_time = Carbon::parse($date.'T'.$night_end);
        $approved_night_start_date_time = Carbon::parse($date.'T'.$approved_night_start);
        $approved_night_end_date_time = Carbon::parse($date.'T'.$approved_night_end);

        $total_night_ot_hour = null;

        if ($night_end_date_time < $approved_night_start_date_time || $approved_night_end_date_time < $night_start_date_time) {
            return $total_night_ot_hour;
        }
        if ($night_start_date_time >= $approved_night_start_date_time && $night_end_date_time <= $approved_night_end_date_time) {
            $total_night_ot_hour = $this->calculateHour($date, $night_start, $night_end);
            return $total_night_ot_hour;
        }
        if ($night_start_date_time < $approved_night_start_date_time && $night_end_date_time > $approved_night_end_date_time) {
            $total_night_ot_hour = $this->calculateHour($date, $approved_night_start, $approved_night_end);
            return $total_night_ot_hour;
        }
        if ($night_start_date_time < $approved_night_start_date_time && $night_end_date_time <= $approved_night_end_date_time) {
            $total_night_ot_hour = $this->calculateHour($date, $approved_night_start, $night_end);
            return $total_night_ot_hour;
        }
        if ($night_start_date_time >= $approved_night_start_date_time && $night_end_date_time > $approved_night_end_date_time) {
            $total_night_ot_hour = $this->calculateHour($date, $night_start, $approved_night_end);
            return $total_night_ot_hour;
        }
        return $total_night_ot_hour;
    }

    /**
     * Calculate time difference HH:mm:ss
     *
     * @param $date
     * @param $start_time
     * @param $end_time
     * @return string
     */
    private function calculateHour($date, $start_time, $end_time)
    {
        $start = new Carbon($date . ' ' . $start_time);
        $end = new Carbon($date . ' ' . $end_time);
        return $start->diff($end)->format('%H:%I:%S');
    }
}
