<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models;
use App\Integrations\nalogRu;
use App\Library\Utilities;

class NalogRuChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nalogru:handle:qrcode {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Распарсивание qr кодов и получение чеков в Nalog.ru';

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
     * Когда будет нормальная интеграция нужно будет переделать:
     * 1. Получение только тех пользователей, у которых настроена интеграция с налог ру
     * 2. Переделать метод проверки существования чека, как то нужно это получить
     * 3. Придумать, куда записывать ошибочные чеки, поскольку узнать о том, что они не обработались тоже нужно
     * @return mixed
     */
    public function handle()
    {
        $api = new nalogRu\Library\API();

        $users = Models\User::get();

        foreach ($users as $user) {

            $networkModel = Models\SocialNetwork::where(['user_id' => $user->id, 'social_network' => 'nalog_ru'])->first();

            if (empty($networkModel)) {
                $this->line('Для этого пользователя' . $user->id . 'не найдено интеграции с Nalog.ru');
                continue;
            }

            $authData = Utilities\Json::decode($networkModel->comment);

            $loginResult = $api->login($authData['phone'], $authData['code']);

            if ($loginResult->code() !== 200) {
                $this->line('Ошибка авторизации с Nalog.ru');
                continue;
            }

            $checks = Models\CheckQueue::whereUserId($user->id)->get();

            foreach ($checks as $check) {
                /**
                 * @var Models\CheckQueue $check
                 */
                try {
                    $existAnswer = $api->checkExist($check->qrcode);

                    if ($existAnswer->code() !== 204) {
                        continue;
                    }

                    $checkDetail = $api->getCheckDetailInfo($check->qrcode, $authData['phone'], $authData['code']);

                    $checkModel = new Models\Check();
                    $checkModel->user_id = $user->id;
                    $checkModel->json = Utilities\Json::encode($checkDetail->response());
                    $checkModel->save();

                }
                catch (\Exception $e) {
                    $this->line('Невозможно сохранить чек');
                    $this->error($e->getMessage());
                }
                //Удаляем если все норм
                $check->delete();
            }
            $this->line('Закончено получение чеков для пользователя ' . $user->name . '(' . $user->id . ')');
        }

    }
}
