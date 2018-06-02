<?php

namespace App\RabbitMQ\Analytics;

use App\RabbitMQ\RabbitmqAbstractClass;

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

    public $consumerTag = 'messageConsumer';

    /**
     * @var string
     */
    public static $logPath;

    public static $errorLog;

    public static $queueLog;

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

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        if ($unpackMessage === 'quit') {

            self::infoLog('Закрытие соединения очереди');
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);

        }
    }

    public static function errorLog($message)
    {
        file_put_contents(self::$errorLog, self::addDateToMessage($message), FILE_APPEND | LOCK_EX);
    }

    public static function infoLog($message)
    {
        file_put_contents(self::$queueLog, self::addDateToMessage($message), FILE_APPEND | LOCK_EX);
    }

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