<?php

namespace App\Console\Commands;

use App\Components\VK\APIHelper;
use App\Events;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        $events = Events::where('date_notif', '<=', date('Y-m-d H:i:s'))->whereCompleted(0)->get();

        if(empty($events)) {
            return null;
        }

        foreach ($events as $event) {
            $userId = $event->user->id;
            $vkId = $this->getVkId($userId);
            $message = $this->buildMessage($event);

            if($message) {
                APIHelper::init($apiKey)->SendMessageUser($vkId, $message, false)->execute();
                $event->completed = 1;
                $event->save();
            }

        }




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
            /**
             * @var $item \App\SocialNetwork
             */
            if($item->social_network === 'vk') {
                $socialId = $item->social_id;
            }
        }

        return $socialId;
    }

    protected function buildMessage(Events $event)
    {
        if($event->type_event === 1) {
            $message = $event->head.PHP_EOL;
            $message .= $event->message;

            return $message;

        }
        return false;
    }
}
