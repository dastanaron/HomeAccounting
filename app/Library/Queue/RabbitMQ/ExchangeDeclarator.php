<?php


namespace App\Library\Queue\RabbitMQ;

use PhpAmqpLib;

/**
 * Class ExchangeDeclarator
 * @package App\Library\Queue\RabbitMQ
 */
class ExchangeDeclarator
{
    const DIRECT = 'direct';
    const FANOUT = 'fanout';
    const TOPIC = 'topic';
    const HEADERS = 'headers';

    /**
     * @var string
     */
    public $exchangeName;

    /**
     * @var string
     */
    public $type;

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
    public $autoDelete = true;

    /**
     * @var bool
     */
    public $internal = false;

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
    public $ticket;

    /**
     * ExchangeDeclarator constructor.
     * @param string $exchangeName
     * @param string $type
     */
    public function __construct(string $exchangeName, string $type = self::DIRECT)
    {
        $this->exchange($exchangeName);
        $this->type($type);
    }

    /**
     * @param string $exchangeName
     * @return ExchangeDeclarator
     */
    public function exchange(string $exchangeName) : self
    {
        $this->exchangeName = $exchangeName;
        return $this;
    }

    /**
     * @param string $type
     * @return ExchangeDeclarator
     */
    public function type(string $type = self::DIRECT) : self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param bool $passiveFlag
     * @return ExchangeDeclarator
     */
    public function passive(bool $passiveFlag = true) : self
    {
        $this->passive = $passiveFlag;
        return $this;
    }

    /**
     * @param bool $durableFlag
     * @return ExchangeDeclarator
     */
    public function durable(bool $durableFlag = true) : self
    {
        $this->durable = $durableFlag;
        return $this;
    }

    /**
     * @param bool $autoDeleteFlag
     * @return ExchangeDeclarator
     */
    public function autoDelete(bool $autoDeleteFlag = true) : self
    {
        $this->autoDelete = $autoDeleteFlag;
        return $this;
    }

    /**
     * @param bool $internalFlag
     * @return ExchangeDeclarator
     */
    public function internal(bool $internalFlag = true) : self
    {
        $this->internal = $internalFlag;
        return $this;
    }

    /**
     * @param bool $noWaitFlag
     * @return ExchangeDeclarator
     */
    public function noWait(bool $noWaitFlag = false) : self
    {
        $this->noWait = $noWaitFlag;
        return $this;
    }

    /**
     * @param array $arguments
     * @return ExchangeDeclarator
     */
    public function arguments(array $arguments) : self
    {
        $this->arguments = new PhpAmqpLib\Wire\AMQPTable($arguments);
        return $this;
    }

    /**
     * @param string $ticket
     * @return ExchangeDeclarator
     */
    public function ticket(string $ticket) : self
    {
        $this->ticket = $ticket;
        return $this;
    }

}