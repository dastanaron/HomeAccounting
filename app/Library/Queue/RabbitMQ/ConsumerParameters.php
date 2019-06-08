<?php

namespace App\Library\Queue\RabbitMQ;


use PhpAmqpLib\Wire;
use App\Library;

/**
 * Class Consumer
 * @package App\Library\Queue\RabbitMQ
 */
class ConsumerParameters implements Library\Queue\Interfaces\ConsumerParametersInterface
{
    /**
     * @var string
     */
    private $consumerTag = '';
    /**
     * @var bool
     */
    private $local = false;
    /**
     * @var bool
     */
    private $ack = false;
    /**
     * @var bool
     */
    private $exclusive = false;
    /**
     * @var bool
     */
    private $nowait = false;

    /**
     * @var int
     */
    private $ticket;

    /**
     * @var Wire\AMQPTable
     */
    private $arguments;

    /**
     * @return string
     */
    public function getConsumerTag(): string
    {
        return $this->consumerTag;
    }

    /**
     * @param string $consumerTag
     */
    public function setConsumerTag(string $consumerTag): void
    {
        $this->consumerTag = $consumerTag;
    }

    /**
     * @return bool
     */
    public function isLocal(): bool
    {
        return $this->local;
    }

    /**
     * @param bool $local
     */
    public function setLocal(bool $local): void
    {
        $this->local = $local;
    }

    /**
     * @return bool
     */
    public function isAck(): bool
    {
        return $this->ack;
    }

    /**
     * @param bool $ack
     */
    public function setAck(bool $ack): void
    {
        $this->ack = $ack;
    }

    /**
     * @return bool
     */
    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    /**
     * @param bool $exclusive
     */
    public function setExclusive(bool $exclusive): void
    {
        $this->exclusive = $exclusive;
    }

    /**
     * @return bool
     */
    public function isNowait(): bool
    {
        return $this->nowait;
    }

    /**
     * @param bool $nowait
     */
    public function setNowait(bool $nowait): void
    {
        $this->nowait = $nowait;
    }

    /**
     * @return int
     */
    public function getTicket(): ?int
    {
        return $this->ticket;
    }

    /**
     * @param int $ticket
     */
    public function setTicket(int $ticket): void
    {
        $this->ticket = $ticket;
    }

    /**
     * @return Wire\AMQPTable
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = new Wire\AMQPTable($arguments);
    }


}