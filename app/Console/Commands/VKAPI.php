<?php

namespace App\Console\Commands;

use App\Components\VK\APIHelper;
use Illuminate\Console\Command;

class VKAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vkapi:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправка всех уведомлений по созданным событиям от пользователей';

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
        $apiKey = env('VK_API_KEY');

        var_dump(APIHelper::init($apiKey)->getUsers('219981829')->execute());
    }
}
