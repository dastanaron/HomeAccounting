<?php

namespace App\RabbitMQ;

/**
 * Class Consumer
 * @package App\RabbitMQ
 */
abstract class Consumer extends RabbitmqAbstractClass
{
    /**
     * run consumer
     */
    public function run()
    {
        $this->channel->basic_consume(
        $this->queue,
        $this->consumerTag,
        false,
        false,
        false,
        false,
        [$this, 'processEnvelope']);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    /**
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     * @param $queue
     * @return mixed
     */
    abstract function processEnvelope(\PhpAmqpLib\Message\AMQPMessage $message);

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