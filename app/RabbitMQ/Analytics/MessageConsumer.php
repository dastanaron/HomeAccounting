<?php

namespace App\RabbitMQ\Analytics;

use App\RabbitMQ\DataConstants;
use App\RabbitMQ\RabbitmqAbstractClass;
use App\Components\DataCharts\ExpensesByCategory;
use Storage;

class MessageConsumer extends RabbitmqAbstractClass
{

    /**
     * @var array
     */
    private $config;

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
     * @var string
     */
    public static $logPath;

    /**
     * @var string
     */
    public static $errorLog;

    /**
     * @var string
     */
    public static $queueLog;

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

    /**
     * run consumer
     */
    public function run()
    {
        $this->channel->basic_consume($this->queue, $this->consumerTag, false, false, false, false, __CLASS__.'::unpackMessage');

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }


    /**
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     */
    public static function unpackMessage(\PhpAmqpLib\Message\AMQPMessage $message)
    {
        $unpackMessage = unserialize($message->body);

        self::infoLog('Получено сообщение: ' . var_export($unpackMessage, true));

        //Метод обработчик данных

        self::messageHandler($unpackMessage);

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        if ($unpackMessage === 'quit') {

            self::infoLog('Закрытие соединения очереди');
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);

        }
    }

    /**
     * @param array $unpackMessage
     * @return bool
     */
    public static function messageHandler(array $unpackMessage)
    {

        $chartData = ExpensesByCategory::init(
            $unpackMessage['userId'],
            $unpackMessage['dateStart'],
            $unpackMessage['dateEnd'],
            $unpackMessage['rev']
        );

        $hashFile = DataConstants::ANALYTICS_STORAGE_FOLDER . $unpackMessage['userId'] . '/' . md5(serialize($unpackMessage)) . '.json';

        try {
            $dataToFile = $chartData->getJsonByChart();
        }
        catch (\Exception $e) {
            self::errorLog('Ошибка, не верные данные на входе очереди: ' . var_export($unpackMessage, true));
            return false;
        }

        return Storage::disk()->put($hashFile, $dataToFile);

    }

    /**
     * @param $message
     */
    public static function errorLog($message)
    {
        self::recordToLog(self::$errorLog, $message);
    }

    /**
     * @param $message
     */
    public static function infoLog($message)
    {
        self::recordToLog(self::$queueLog, $message);
    }

    private static function recordToLog($log, $message)
    {
        if(self::$debug === true) {
            file_put_contents($log, self::addDateToMessage($message), FILE_APPEND | LOCK_EX);
        }
    }

    /**
     * @param $message
     * @return string
     */
    public static function addDateToMessage($message)
    {
        return '|'.date('Y-m-d H:i:s').'| ' . $message . PHP_EOL;
    }

    /**
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @param \PhpAmqpLib\Connection\AbstractConnection $connection
     */
    public static function shutdown(\PhpAmqpLib\Channel\AMQPChannel $channel, \PhpAmqpLib\Connection\AbstractConnection $connection )
    {
        $channel->close();
        $connection->close();
    }
}