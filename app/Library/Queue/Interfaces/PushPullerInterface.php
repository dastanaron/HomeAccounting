<?php


namespace App\Library\Queue\Interfaces;


interface PushPullerInterface
{
    public function push(string $message);
    public function pull();
}