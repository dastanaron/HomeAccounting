<?php

namespace App\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class RabbitmqAbstractClass implements RabbitmqInterface
{

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
}