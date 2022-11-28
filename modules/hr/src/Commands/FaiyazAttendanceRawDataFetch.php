<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\HR\Models\HrAttendanceRawData;

class FaiyazAttendanceRawDataFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faiyaz:raw_attendance_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Attendance Raw Data ';

    protected $db_connection;
    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        return $this->db_connection = DB::connection('mysql_faiyaz');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Execution started!!');

        $dateFrom = date('Y-m-d', strtotime("-3 days"));
        $dateTo = date('Y-m-d');

        $this->db_connection->table('auditdata')
            ->whereDate('AttendDate', '>=', $dateFrom)
            ->whereDate('AttendDate', '<=', $dateTo)
            ->orderBy('AttendDate')
            ->chunk(500, function ($raw_attendance_data) {
                $this->info("Date= ". $raw_attendance_data->first()->AttendDate);
                $this->updateRawAttendanceData($raw_attendance_data);
            });

        $this->info('Execution ended!!');
    }

    public function updateRawAttendanceData($raw_attendance_data)
    {
        try {
            DB::beginTransaction();
            foreach ($raw_attendance_data as $data) {
                $isExists = HrAttendanceRawData::where([
                    'userid' => $data->userid,
                    'attendance_date' => $data->AttendDate,
                    'punch_time' => $data->checktime,
                ])->first();
                if ($isExists) {
                    continue;
                }
                $collection = new HrAttendanceRawData();
                $collection->userid = $data->userid;
                $collection->punch_time = $data->checktime;
                $collection->attendance_date = $data->AttendDate;
                $collection->created_by = null;
                $collection->save();
            }
            DB::commit();
            $this->info('Data migrated successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->info($exception->getMessage());
        }
    }
}
