<?php

namespace App\RabbitMQ\Analytics;

use App\Charts;
use App\Components\DataCharts\ChartData;
use App\Components\DataCharts\ExpensesByMonthCategory;
use App\RabbitMQ\Consumer;
use App\RabbitMQ\DataConstants;
use App\Components\DataCharts\ExpensesByCategory;
use Storage;

class MessageConsumer extends Consumer
{
    /**
     * @var string
     */
    protected $queue = 'analyticsQueue';

    /**
     * @var string
     */
    protected $exchange = 'analyticsChange';

    /**
     * @var \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    public $connection;

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    public $channel;

    /**
     * @var string
     */
    public $consumerTag = 'messageConsumer';

    /**
     * MessageConsumer constructor.
     */
    public function __construct()
    {
        parent::__construct();

        self::$logPath = realpath('./storage/logs/');

        if(!file_exists(self::$logPath . '/rabbitmq')) {
            mkdir(self::$logPath . '/rabbitmq', 0777, true);
        }

        self::$logPath = realpath('./storage/logs/rabbitmq');
        self::$errorLog = self::$logPath . '/error.log';
        self::$queueLog = self::$logPath . '/queue.log';
    }

    public function processEnvelope(\PhpAmqpLib\Message\AMQPMessage $message)
    {
        $unpackMessage = $this->unpack($message->body);

        self::infoLog('Получено сообщение: ' . var_export($unpackMessage, true));

        //Метод обработчик данных

        $this->messageHandler($unpackMessage);

        $message->get('channel')->basic_ack($message->delivery_info['delivery_tag']);

        if ($unpackMessage === 'quit') {

            self::infoLog('Закрытие соединения очереди');
            $message->get('channel')->basic_cancel($message->delivery_info['consumer_tag']);

        }
    }

    /**
     * @param array $unpackMessage
     * @return bool
     */
    public function messageHandler(array $unpackMessage)
    {

        try {
            $chartData = $this->dataForChartType($unpackMessage);

            $controlSum = $unpackMessage['controlSum'];

            $dataToBase = $chartData->getJsonByChart();

        }
        catch (\Exception $e) {
            self::errorLog('Ошибка, не верные данные на входе очереди: ' . var_export($unpackMessage, true));
            return false;
        }


        try {
            $this->recordToBase($controlSum, $unpackMessage['userId'], $dataToBase);
        }
        catch(\Exception $e) {
            self::errorLog('Ошибка, не удалось выполнить запись в БД');
        }

    }

    /**
     * @param array $unpackMessage
     * @return ChartData|null
     */
    private function dataForChartType(array $unpackMessage)
    {
        switch ($unpackMessage['chartType'])
        {
            case 'dayJump':
                $chartData = ExpensesByCategory::init(
                    $unpackMessage['userId'],
                    $unpackMessage['dateStart'],
                    $unpackMessage['dateEnd']
                );
                return $chartData;
            case 'categoryMonth':
                $chartData = ExpensesByMonthCategory::init(
                    $unpackMessage['userId'],
                    $unpackMessage['dateStart'],
                    $unpackMessage['dateEnd']
                );
                return $chartData;

            default:
                $chartData = ExpensesByCategory::init(
                    $unpackMessage['userId'],
                    $unpackMessage['dateStart'],
                    $unpackMessage['dateEnd']
                );
                return $chartData;
        }
    }

    private function recordToBase($controlSum, $userId, $data)
    {
        $chartByControlSum = Charts::whereControlSum($controlSum)->first();

        $chartModel = !empty($chartByControlSum) ? $chartByControlSum : new Charts();

        $chartModel->type = 'line_categories';
        $chartModel->setData($data);
        $chartModel->user_id = $userId;
        $chartModel->control_sum = $controlSum;

        return $chartModel->save();

    }

}