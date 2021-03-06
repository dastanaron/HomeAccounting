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

    private $debugMode = false;

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
        $this->debugMode = env('APP_DEBUG') === true || env('DEBUG') || $this->option('debug');

        $api = new nalogRu\Library\API();

        $users = Models\User::get();

        $integrationFacade = nalogRu\Facade::getInstance();

        foreach ($users as $user) {

            $integration = $integrationFacade->getCRUD()->getIntegrationByUserId($user->id);

            if ($integration === null) {
                $this->debug('Для этого пользователя ' . $user->id . ' не найдено интеграции с Nalog.ru');
                continue;
            }

            if ((bool)$integration->is_active === false) {
                $this->debug('Для этого пользователя ' . $user->id . ' есть интеграция с Nalog.ru, но она не активна');
                continue;
            }

            $meta = $integrationFacade->getMetaFromIntegration($integration);

            $loginResult = $api->login($meta->phone, $meta->smsCode);

            if ($loginResult->code() !== 200) {
                $this->debug('Ошибка авторизации с Nalog.ru');
                continue;
            }

            $checks = Models\CheckQueue::whereUserId($user->id)->where([
                'user_id' => $user->id,
                'is_processed' => 0,
            ])->get();

            $this->debug('Найдено ' . $checks->count() . ' чека для пользователя ' . $this->prepareUserStringForLog($user));

            foreach ($checks as $check) {
                /**
                 * @var Models\CheckQueue $check
                 */
                try {
                    $existAnswer = $api->checkExist($check->qrcode);

                    if ($existAnswer->code() !== 204) {
                        $this->debug('Чек с кодом:  ' . $check->qrcode . ' не найден в базе Nalog.ru ');
                        continue;
                    }

                    $checkDetail = $api->getCheckDetailInfo($check->qrcode, $meta->phone, $meta->smsCode);

                    $checkModel = new Models\Check();
                    $checkModel->user_id = $user->id;
                    $checkModel->json = Utilities\Json::encode($checkDetail->response());
                    $checkModel->save();

                }
                catch (\Exception $e) {
                    $this->debug('Невозможно сохранить чек');
                    $this->error($e->getMessage());
                }
                //Помечаем отработанным
                $check->is_processed = 1;
                $check->save();
            }
            $this->debug('Закончено получение чеков для пользователя ' . $user->name . '(' . $user->id . ')');
        }
    }

    private function debug(string $message)
    {
        if ($this->debugMode) {
            $this->line($message);
        }
    }

    private function prepareUserStringForLog(Models\User $user)
    {
        return $user->name . ' (id: ' . $user->id . ' )';
    }
}
