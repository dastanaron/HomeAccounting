<?php


namespace App\Library\Queue\RabbitMQ;


class MessageParameters
{
    /**
     * @var string
     */
    protected $routing_key = '';

    /**
     * @var bool
     */
    protected $mandatory = false;

    /**
     * @var bool
     */
    protected $immediate = false;

    /**
     * @var int
     */
    protected $ticket = null;

    /**
     * @return string
     */
    public function getRoutingKey(): string
    {
        return $this->routing_key;
    }

    /**
     * @param string $routing_key
     */
    public function setRoutingKey(string $routing_key): void
    {
        $this->routing_key = $routing_key;
    }

    /**
     * @return bool
     */
    public function isMandatory(): bool
    {
        return $this->mandatory;
    }

    /**
     * @param bool $mandatory
     */
    public function setMandatory(bool $mandatory): void
    {
        $this->mandatory = $mandatory;
    }

    /**
     * @return bool
     */
    public function isImmediate(): bool
    {
        return $this->immediate;
    }

    /**
     * @param bool $immediate
     */
    public function setImmediate(bool $immediate): void
    {
        $this->immediate = $immediate;
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
}