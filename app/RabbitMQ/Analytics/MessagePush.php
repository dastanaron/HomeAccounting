<?php

namespace App\RabbitMQ\Analytics;

use PhpAmqpLib\Message\AMQPMessage;
use App\RabbitMQ\RabbitmqAbstractClass;

class MessagePush extends RabbitmqAbstractClass
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
     * @param $data
     */
    public function push($data)
    {
        $message = new AMQPMessage(serialize($data), array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        $this->channel->basic_publish($message, $this->exchange);

        $this->channel->close();

        $this->connection->close();
    }

}