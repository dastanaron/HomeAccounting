<?php

namespace App\Console\Commands;

use App\SocialNetwork;
use Illuminate\Console\Command;
use App\Events;
use GuzzleHttp\Client;

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

            $this->logMessage('Получаем токены для отправки');

            $socialNetwork = SocialNetwork::where('social_network', '=', 'web-push')
                ->where('user_id', '=', $userId);

            //Может быть привязано несколько устройств, поэтому пушим во все
            foreach ($socialNetwork->get() as $item) {
                /**
                 * @var SocialNetwork $item
                 */
                $pushToken = $item->social_id;

                $this->logMessage('Отправляем push уведомление пользователю id=' . $userId);
                $sent = $this->sendMessage($pushToken, $event->head, $event->message);
                $this->logMessage('Отправка завершилась ответом [' . $sent . ']');
            }

            //Не важно отправлено или нет,
            // запись в базе делаем с отметкой завершено,
            // чтобы не завалить бесконечным циклом в случае ошибки отправки
            $event->completed = 1;
            $event->save();

        }

        $this->logMessage('==========Конец обработки===========');

    }

    private function sendMessage($toPushToken, $title, $body, $icon='/favicon.png', $link=null)
    {

        $icon = env('APP_URL') . $icon;

        $message = [
            'data' => [
                "title" => $title,
                "body" => $body,
                "icon" => $icon,
                "click_action" => $link
            ],
            'to' => $toPushToken
        ];

        $client = new Client();
        $res = $client
            ->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                'headers' => [
                    'Authorization' => 'key=' . $this->serverKey,
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($message),
            ])
            ->getBody()
            ->getContents();

        $answer = (bool) json_decode($res)->success;

        if(!$answer)
        {
            $this->fileLog(json_decode($res, true));
        }

        return $answer;
    }

    protected function logMessage($message)
    {
        $date = date('Y-m-d H:i:s');

        $this->line($date.' | '.$message);
    }

    private function fileLog($data)
    {
        \Log::info('Отправка уведомления не завершена', $data);
    }
}