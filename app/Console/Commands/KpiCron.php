<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class KpiCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kpi:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        \Log::info("Cron ");
        // Call the function from your controller
        app()->call('App\Http\Controllers\Fsm\KpiDashboardController@store');
    }
}
