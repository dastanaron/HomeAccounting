<?php


namespace App\Library\Queue;

use App\Library;
use App\Library\Queue\Interfaces;
use http\Message;
use PhpAmqpLib;

/**
 * Class RabbitMQ
 * @package App\Library\Queue
 */
class RabbitMQ implements Interfaces\ConsumerInterface, Interfaces\PushPullerInterface
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

    /**
     * RabbitMQ constructor.
     * @param string $queueName
     * @param string $exchangeName
     */
    public function __construct(string $queueName, string $exchangeName)
    {
        $this->rabbit = RabbitMQ\Wrapper::getInstance();
        $this->queue = new RabbitMQ\QueueDeclarator($queueName);
        $this->exchange = new RabbitMQ\ExchangeDeclarator($exchangeName);
        $this->channel = $this->rabbit->getChannel();
    }

    /**
     * @param string $routingKey
     */
    public function bind($routingKey = null)
    {
        if (empty($routingKey)) {
            $routingKey = $this->queue()->queueName;
        }
        $this->rabbit->bind($this->queue(), $this->exchange(), $routingKey);
    }

    /**
     * @return RabbitMQ\QueueDeclarator
     */
    public function queue()
    {
        return $this->queue;
    }

    /**
     * @return RabbitMQ\ExchangeDeclarator
     */
    public function exchange()
    {
        return $this->exchange;
    }

    /**
     * @return PhpAmqpLib\Channel\AMQPChannel
     */
    public function channel()
    {
        return $this->channel;
    }


    /**
     * @param callable $callback
     * @param Interfaces\ConsumerParametersInterface $consumer
     * @throws Library\Exceptions\WrongTypeException
     * @throws RabbitMQ\RabbitMQException
     */
    public function consume(callable $callback, Interfaces\ConsumerParametersInterface $consumer)
    {
        /**
         * @var Library\Queue\RabbitMQ\ConsumerParameters $consumer
         */
        if (!($consumer instanceof Library\Queue\RabbitMQ\ConsumerParameters)) {
            throw new Library\Exceptions\WrongTypeException('consumer must be an object of class ' . Library\Queue\RabbitMQ\ConsumerParameters::class);
        }

        try {
            $this->channel()->basic_consume($this->queue()->queueName,
                $consumer->getConsumerTag(),
                $consumer->isLocal(),
                $consumer->isAck(),
                $consumer->isExclusive(),
                $consumer->isNowait(),
                $callback,
                $consumer->getTicket(),
                $consumer->getArguments()
            );
        }
        catch (\Exception $exception) {
            throw new RabbitMQ\RabbitMQException('Unable to run consume');
        }

    }

    /**
     * @param Interfaces\MessageInterface $message
     * @throws Library\Exceptions\WrongTypeException
     * @throws RabbitMQ\RabbitMQException
     */
    public function push(Interfaces\MessageInterface $message)
    {
        if (!($message instanceof RabbitMQ\Message)) {
            throw new Library\Exceptions\WrongTypeException('message must be an object of class ' . RabbitMQ\Message::class);
        }

        $routingKey = $message->parameters()->getRoutingKey();

        if (empty($routingKey)) {
            $routingKey = $this->queue->queueName;
        }

        try {
            $this->channel()->basic_publish($message->getAMQPMessage(),
                $this->exchange()->exchangeName,
                $routingKey,
                $message->parameters()->isMandatory(),
                $message->parameters()->isImmediate(),
                $message->parameters()->getTicket()
            );
        }
        catch (\Exception $exception) {
            throw new RabbitMQ\RabbitMQException('Unable to push message');
        }
    }

    /**
     * @param bool $ack
     * @param int|null $ticket
     * @return RabbitMQ\Message
     */
    public function pull(bool $ack = true, int $ticket = null)
    {
        /**
         * @var PhpAmqpLib\Message\AMQPMessage $messageOrigin
         */
        $messageOrigin = $this->channel()->basic_get($this->queue()->queueName, $ack, $ticket);

        $message = new RabbitMQ\Message();
        $message->setAmqpMessage($messageOrigin);

        return $message;
    }

    /**
     * @return bool
     * @throws RabbitMQ\RabbitMQException
     */
    public function clearQueue()
    {
        try {
            $this->channel()->queue_purge($this->queue()->queueName);
        }
        catch (\Exception $e) {
            throw new RabbitMQ\RabbitMQException('Cannot clear queue "' . $this->queue()->queueName . '"');
        }
        return true;
    }

    /**
     * @return bool
     * @throws RabbitMQ\RabbitMQException
     */
    public function deleteQueue()
    {
        try {
            $this->channel()->queue_delete($this->queue()->queueName);
        }
        catch (\Exception $e) {
            throw new RabbitMQ\RabbitMQException('Cannot remove queue "' . $this->queue()->queueName . '"');
        }
        return true;
    }
}