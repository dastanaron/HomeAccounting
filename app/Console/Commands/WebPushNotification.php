<?php

namespace App\Console\Commands;

use App\Models;
use App\Library\Utilities;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class WebPushNotification extends Command
{

    use Traits\ConsoleOutputLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webPush:notifications {--debug}';

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
        $this->debugMode = (bool) $this->option('debug');

        $this->logMessage('==========Обработка событий===========');

        $events = Models\Events::where('date_notif', '<=', date('Y-m-d H:i:s'))
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
             * @var Models\Events $event;
             */
            $userId = $event->user->id;

            $this->logMessage('Получаем токены для отправки');

            $socialNetwork = Models\SocialNetwork::where('social_network', '=', 'web-push')
                ->where('user_id', '=', $userId);

            //Может быть привязано несколько устройств, поэтому пушим во все
            foreach ($socialNetwork->get() as $item) {
                /**
                 * @var Models\SocialNetwork $item
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

    /**
     * @param $toPushToken
     * @param $title
     * @param $body
     * @param string $icon
     * @param null $link
     * @return bool
     * @throws Utilities\Exceptions\DecodingException
     * @throws Utilities\Exceptions\EncodingException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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
                'body' => Utilities\Json::encode($message),
            ])
            ->getBody()
            ->getContents();

        $answer = (bool) Utilities\Json::decode($res, false)->success;

        if(!$answer)
        {
            $this->fileLog(Utilities\Json::decode($res, true));
        }

        return $answer;
    }

    private function fileLog($data)
    {
        \Log::info('Отправка уведомления не завершена', $data);
    }
}