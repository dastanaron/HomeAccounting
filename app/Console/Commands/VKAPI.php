<?php

namespace App\Console\Commands;

use App\Components\VK\APIHelper;
use App\User;
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
        /*$apiKey = env('VK_API_KEY');

        var_dump(APIHelper::init($apiKey)->SendMessageUser('219981829', 'Hi', true)->execute());*/





    }

    public function getVkId($user_id)
    {
        $user = User::whereId($user_id)->first();

        $socialNetworks = $user->social_network;

        /**
         * if $item is empty;
         */
        $socialId = false;

        foreach ($socialNetworks as $item) {
            if($item->social_network === 'vk') {
                $socialId = $item->social_id;
            }
        }

        return $socialId;
    }
}
