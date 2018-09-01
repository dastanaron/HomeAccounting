<?php

namespace App\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class RabbitmqAbstractClass
 * @package App\RabbitMQ
 */
abstract class RabbitmqAbstractClass implements RabbitmqInterface
{


    /**
     * @var bool
     */
    public static $debug = false;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    protected $queue;

    /**
     * @var string
     */
    protected $exchange;

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
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function config()
    {
        return config('queue.connections.rabbitmq');
    }

    /**
     * MessagePush constructor.
     */
    public function __construct()
    {

        self::$debug = !empty(env('APP_DEBUG')) ? env('APP_DEBUG') : false;

        $this->config = $this->config();

        $this->connection = $this->createConnection();

        $this->channel = $this->connection->channel();

        $this->channel->queue_declare($this->queue, false, true, false, false);

        $this->channel->exchange_declare($this->exchange, 'direct', false, true, false);

        $this->channel->queue_bind($this->queue, $this->exchange);
    }


    public function __debugInfo()
    {
        return [
            'config' => $this->config,
            'queue' => $this->queue,
            'exchange' => $this->exchange,
            'methods' => $this->getClassMethods(),
        ];
    }

    protected function getClassMethods()
    {
        $methods = get_class_methods(self::class);

        $array = array();

        foreach($methods as $method) {
            if(substr($method, 0, 2) !== "__") {
                $array[] = $method;
            }
        }

        return $array;
    }

    /**
     * @return mixed
     */
    public static function init()
    {
        return new static();
    }

    /**
     * @return \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    protected function createConnection()
    {
        return new AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['login'],
            $this->config['password'],
            $this->config['vhost']
        );
    }

    /**
     * Pack message to queue
     * @param $data
     * @return string
     */
    protected function pack($data)
    {
        return json_encode($data);
    }

    /**
     * Unpack message to queue
     * @param $data
     * @return mixed
     */
    protected function unpack($data)
    {
        try {
            return json_decode($data, true);
        }
        catch(\Exception $e) {
            self::errorLog('Неверный параметр в очереди');
            self::errorLog(var_export($e, true));
        }

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
        file_put_contents($log, self::addDateToMessage($message), FILE_APPEND | LOCK_EX);

        if(self::$debug === true) {
            echo self::addDateToMessage($message);
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
}