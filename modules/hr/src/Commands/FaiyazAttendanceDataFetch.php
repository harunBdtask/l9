<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;
use SkylarkSoft\GoRMG\HR\Models\HrAttendance;

class FaiyazAttendanceDataFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faiyaz:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Machine Attendance Table From Faiyaz Group DB';

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
        $this->db_connection->table('attendance')
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->orderBy('date')
            ->chunk(500, function ($attendance_data) {
                $this->info("Date= ". $attendance_data->first()->date);
                $this->updateMachineAttendanceData($attendance_data);
            });
        $this->info('Execution ended!!');
    }

    private function updateMachineAttendanceData($attendance_data)
    {
        try {
            DB::beginTransaction();
            foreach ($attendance_data as $data) {
                $machine_attendances = HrAttendance::firstOrNew([
                    'userid' => $data->userid,
                    'date' => $data->date,
                ]);
                $machine_attendances->idattendance_sheet = $data->idattendance_sheet;
                $machine_attendances->userid = $data->userid;
                $machine_attendances->date = $data->date;
                $machine_attendances->att_in = $data->att_in;
                $machine_attendances->att_break = $data->att_break;
                $machine_attendances->att_resume = $data->att_resume;
                $machine_attendances->att_out = $data->att_out;
                $machine_attendances->att_ot = $data->att_ot;
                $machine_attendances->att_done = $data->att_done;
                $machine_attendances->workhour = $data->workhour;
                $machine_attendances->othour = $data->othour;
                $machine_attendances->save();
            }
            DB::commit();

            $this->info('Data migrated successfully');
        } catch (Exception $e) {
            DB::rollback();
            $this->info($e->getMessage());
        }
    }
}
