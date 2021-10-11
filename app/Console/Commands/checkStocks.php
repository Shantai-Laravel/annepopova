<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Admin\Http\Controllers\AdminController;

class checkStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check products stocks';

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
        $admin = new AdminController();
        $admin->checkProductsStocks();
    }
}
