<?php

namespace App\Console\Commands;

use App\Modules\Import\Ofx\OfxParser;
use App\Modules\Import\Ofx\Tinkoff\TinkoffObject;
use App\Modules\Import\Ofx\Tinkoff\TinkoffTransactionObject;
use Carbon\Carbon;
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
        echo json_encode(['message' => 'привет']);
    }
}
