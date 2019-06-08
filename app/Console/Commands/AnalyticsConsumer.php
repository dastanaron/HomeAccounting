<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Analytics;

class AnalyticsConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyticsconsumer:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запускает консьюмер очереди аналитики';

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
        $consumer = new Analytics\Consumer();
        $consumer->run();
    }
}
