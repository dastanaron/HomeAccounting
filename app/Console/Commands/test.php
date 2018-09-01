<?php

namespace App\Console\Commands;

use App\Charts;
use Illuminate\Console\Command;

class test extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

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
     * @return mixed
     */
    public function handle()
    {
        $chartByControlSum = Charts::whereControlSum('80696c0ae61a7f5676acc3d35160f3f7')->first();

        dump($chartByControlSum);
    }
}
