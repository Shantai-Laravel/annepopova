<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Admin\Http\Controllers\FrisboController;

class updateStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update subproducts stocks';

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
        $frisbo = new FrisboController();
        $frisbo->synchronizeStocks();
    }
}
