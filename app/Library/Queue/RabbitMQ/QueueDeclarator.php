<?php


namespace App\Library\Queue\RabbitMQ;

use PhpAmqpLib;


class QueueDeclarator
{
    /**
     * @var string
     */
    public $queueName;

    /**
     * @var bool
     */
    public $passive = false;

    /**
     * @var bool
     */
    public $durable = false;

    /**
     * @var bool
     */
    public $exclusive = false;

    /**
     * @var bool
     */
    public $autoDelete = true;

    /**
     * @var bool
     */
    public $noWait = false;

    /**
     * @var PhpAmqpLib\Wire\AMQPTable
     */
    public $arguments;

    /**
     * @var string
     */
    public $ticket = null;

    public function __construct(string $queueName)
    {
        $this->queueName = $queueName;
    }

    /**
     * @param bool $passiveFlag
     * @return $this
     */
    public function passive(bool $passiveFlag = true) : self
    {
        $this->passive = $passiveFlag;
        return $this;
    }

    public function durable(bool $durableFlag = true) : self
    {
        $this->durable = $durableFlag;
        return $this;
    }

    public function exclusive(bool $exclusiveFlag = true) : self
    {
        $this->exclusive = $exclusiveFlag;
        return $this;
    }

    public function autoDelete(bool $autoDeleteFlag = true) : self
    {
        $this->autoDelete = $autoDeleteFlag;
        return $this;
    }

    public function noWait(bool $noWaitFlag = false) : self
    {
        $this->noWait = $noWaitFlag;
        return $this;
    }

    public function arguments(array $arguments) : self
    {
        $this->arguments = new PhpAmqpLib\Wire\AMQPTable($arguments);
        return $this;
    }

    public function ticket(string $ticket) : self
    {
        $this->ticket = $ticket;
        return $this;
    }
}