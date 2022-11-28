<?php

namespace SkylarkSoft\GoRMG\HR\Helpers;


use Carbon\Carbon;

class TimeCalculator
{
    /**
     * Calculate Minutes
     *
     * @param string $time
     * @return float|int|null
     */
    public static function minutes($time = '')
    {
        if ($time == '') {
            return null;
        }
        $time = explode(':', $time);
        return ($time[0] * 60) + ($time[1]) + ($time[2] / 60);
    }


    /**
     * Calculate Hour between two times
     *
     * @param $date
     * @param $start_time
     * @param $end_time
     * @return string
     */
    public static function calculateHour($date, $start_time, $end_time)
    {
        $start = new Carbon($date . ' ' . $start_time);
        $end = new Carbon($date . ' ' . $end_time);
        return $start->diff($end)->format('%H:%I:%S');
    }

    /**
     * Add Two Times
     *
     * @param $actual_start
     * @param $actual_end
     * @param $approved_start
     * @param $approved_end
     * @return false|string
     */
    public static function addTwoTimes($actual_start, $actual_end, $approved_start, $approved_end)
    {
        $one = Carbon::parse($actual_start);
        $two = Carbon::parse($approved_start);
        $first_segment = $one->diffInSeconds($two);

        $three = Carbon::parse($approved_end);
        $four = Carbon::parse($actual_end);
        $second_segment = $three->diffInSeconds($four);

        return gmdate('H:i:s', $first_segment + $second_segment);
    }

    /**
     * add Two Times In TimeStamp
     *
     * @param string $first_time
     * @param string $second_time
     * @return false|string
     */
    public static function addTwoTimesInTimeStamp($first_time = '', $second_time = '')
    {
        if ($first_time == '') {
            return '00:00:00';
        } elseif ($first_time != '' && $second_time == '') {
            return $first_time;
        } elseif ($first_time != '' && $second_time != '') {
            $secs = strtotime($second_time) - strtotime("00:00:00");
            return date("H:i:s", strtotime($first_time) + $secs);
        }
        return '00:00:00';
    }

    /**
     * Calculate Ot Minute
     *
     * @param $time (hh:mm::ss format)
     * @return int
     */
    public static function calculateOtMinute($time = '')
    {
        if ($time == '') {
            return null;
        }
        $time = explode(':', $time);
        $hour = $time[0];
        if ($time[1] >= 55) {
            $hour += 1;
        }
        $minute = $hour * 60;
        return $minute;
    }
}
