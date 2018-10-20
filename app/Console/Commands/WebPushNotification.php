<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events;
use App\User;

class WebPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webPush:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправка всех уведомлений по созданным событиям от пользователей';

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $serverKey;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    private $senderId;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->serverKey = config('web-push.serverKey');
        $this->senderId = config('web-push.senderId');

        parent::__construct();
    }

    /**
     * Сделать отправку сообщений в webPush
     */
    public function handle()
    {
        $this->logMessage('==========Обработка событий===========');

        $events = Events::where('date_notif', '<=', date('Y-m-d H:i:s'))
            ->whereCompleted(0)
            ->where('type_event', '=', '1')
            ->get();

        if($events->count() === 0) {
            $this->logMessage('События удовлетворяющие запросам не найдены');
            $this->logMessage('==========Конец обработки===========');
            return null;
        }

        $this->logMessage('События найдены, начата отправка уведомлений');

        foreach ($events as $event) {
            /**
             * @var Events $event;
             */
            $userId = $event->user->id;
            dd($event);

        }

        $this->logMessage('==========Конец обработки===========');

    }

    private function sendMessage()
    {

    }

    protected function logMessage($message)
    {
        $date = date('Y-m-d H:i:s');

        $this->line($date.' | '.$message);
    }
}