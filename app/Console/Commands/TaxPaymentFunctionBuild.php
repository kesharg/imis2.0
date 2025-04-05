<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class TaxPaymentFunctionBuild extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buildfunction:tax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates Functions to create table and update building owner when new tax data is imported';

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
        DB::unprepared(Config::get('taxpayment-info.fnc_create_taxpaymentstatus'));
        DB::unprepared(Config::get('taxpayment-info.fnc_insrtupd_taxbuildowner'));
        DB::unprepared(Config::get('taxpayment-info.fnc_create_wardproportion'));
        DB::unprepared(Config::get('taxpayment-info.fnc_create_gridproportion'));

        DB::unprepared(Config::get('taxpayment-info.fnc_updonimprt_gridnward_tax'));

        \Log::info("Functions to create table and update building owner after import successfully!!");
        $this->info('Functions to create table and update building owner after import successfully!!');

        return Command::SUCCESS;
    }
}
