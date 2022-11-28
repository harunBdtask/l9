<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATask;

class TnaTaskEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tna_task_entries')->truncate();
        foreach (TNATask::TASK_NAMES as $task) {
            $words = explode(" ", $task);
            $short_name = "";
            foreach ($words as $w) {
                $short_name .= $w[0];
            }
            DB::table('tna_task_entries')->insert([
                'task_name' => $task,
//                'task_short_name' => $short_name,
                'status' => 1,
            ]);
        }
    }
}
