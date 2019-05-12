<?php


namespace App\Library\Queue;


use App\Library\Queue\Interfaces\ConsumerInterface;
use App\Library\Queue\Interfaces\MessageInterface;
use App\Library\Queue\Interfaces\PushPullerInterface;
use PhpAmqpLib;

class RabbitMQ implements ConsumerInterface, PushPullerInterface
{
    /**
     * @var RabbitMQ\Wrapper
     */
    private $rabbit;

    /**
     * @var RabbitMQ\QueueDeclarator
     */
    private $queue;

    /**
     * @var RabbitMQ\ExchangeDeclarator
     */
    private $exchange;

    /**
     * @var PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    public function __construct(string $queueName, string $exchangeName)
    {
        $this->rabbit = RabbitMQ\Wrapper::getInstance();
        $this->queue = new RabbitMQ\QueueDeclarator($queueName);
        $this->exchange = new RabbitMQ\ExchangeDeclarator($exchangeName);
        $this->channel = $this->rabbit->getChannel();
    }

    public function bind($routingKey = null)
    {
        $this->rabbit->bind($this->queue(), $this->exchange(), $routingKey);
    }

    public function queue()
    {
        return $this->queue;
    }

    public function exchange()
    {
        return $this->exchange;
    }

    public function channel()
    {
        return $this->channel;
    }

    public function consume()
    {
        // TODO: Implement consume() method.
    }

    public function push(string $message)
    {
        /*
        $preparedMessage = new RabbitMQ\Message($message);
        $this->channel()->basic_publish($preparedMessage, $this->exchange()->exchangeName, '', '', '', '')
        */
    }

    public function pull()
    {
        // TODO: Implement pull() method.
    }


}