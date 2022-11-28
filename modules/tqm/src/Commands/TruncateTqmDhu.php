<?php

namespace SkylarkSoft\GoRMG\TQM\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTqmDhu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:tqm-dhu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will truncate all data from TQM DHU';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $this->info('Removing all data from TQM dhu...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::beginTransaction();

            DB::table('tqm_cutting_dhus')->truncate();
            DB::table('tqm_cutting_dhu_details')->truncate();
            DB::table('tqm_finishing_dhus')->truncate();
            DB::table('tqm_finishing_dhu_details')->truncate();
            DB::table('tqm_sewing_dhus')->truncate();
            DB::table('tqm_sewing_dhu_details')->truncate();

            DB::commit();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->info('Removed Successfully!!');
            return 1;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->info($e->getMessage());
            return 0;
        }
    }
}
