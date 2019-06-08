<?php


namespace App\Library\Queue\Interfaces;


interface PushPullerInterface
{
    public function push(MessageInterface $message);
    public function pull(bool $ack, int $ticket = null);
}