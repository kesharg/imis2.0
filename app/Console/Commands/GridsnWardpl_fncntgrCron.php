<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class GridsnWardpl_fncntgrCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fncntgr:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates Functions and triggers for grid&wardpl and summarychart';

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
        // function and triggers to update grids & wardpl when jhe_buildings has changes
        DB::unprepared(Config::get('fnc_n_tgr.gridsnwardpl_buildings.fnc_set_buildings'));
        DB::unprepared(Config::get('fnc_n_tgr.gridsnwardpl_buildings.tgr_set_gridsNwardpl_buildings'));

        // function and triggers to update grids & wardpl when jhe_containment has changes
        DB::unprepared(Config::get('fnc_n_tgr.gridsnwardpl_containments.fnc_set_containments'));
        DB::unprepared(Config::get('fnc_n_tgr.gridsnwardpl_containments.tgr_set_gridsNwardpl_containments'));

        // function and triggers to update grids & wardpl when jhe_roadline has changes
        DB::unprepared(Config::get('fnc_n_tgr.gridsnwardpl_roadline.fnc_set_roadline'));
        DB::unprepared(Config::get('fnc_n_tgr.gridsnwardpl_roadline.tgr_set_gridsNwardpl_roadline'));

        // function and triggers to update landuse summary for chart when jhe_container has changes
        DB::unprepared(Config::get('fnc_n_tgr.summaryforchart_landuse.fnc_set_landusesummary'));
        DB::unprepared(Config::get('fnc_n_tgr.summaryforchart_landuse.tgr_set_landusesummary'));

        // function and triggers to update builtupperward summary for chart when jhe_container has changes
        DB::unprepared(Config::get('fnc_n_tgr.summaryforchart_builtupperward.fnc_set_builtupperwardsummary'));
        DB::unprepared(Config::get('fnc_n_tgr.summaryforchart_builtupperward.tgr_set_builtupperwardsummary'));

        \Log::info("Functions & Triggers installed / updated successfully!!");

        return Command::SUCCESS;
    }
}
