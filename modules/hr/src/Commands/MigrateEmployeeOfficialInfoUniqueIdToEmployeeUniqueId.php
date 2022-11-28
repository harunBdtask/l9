<?php

namespace SkylarkSoft\GoRMG\HR\Commands;

use Illuminate\Console\Command;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;

class MigrateEmployeeOfficialInfoUniqueIdToEmployeeUniqueId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-employee-unique-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Employee Official Info Unique Id To Employee Unique Id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        HrEmployeeOfficialInfo::migrateUniqueId();
    }
}
