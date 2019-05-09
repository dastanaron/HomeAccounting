<?php

namespace App\Console\Commands;

use App\Models\Bills;
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $models = Bills::whereUserId(1)->get();

        var_dump($models);

    }
}
