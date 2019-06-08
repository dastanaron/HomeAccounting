<?php


namespace App\Library\Queue\Interfaces;

/**
 * Interface ConsumerInterface
 * @package App\Library\Queue\Interfaces
 */
interface ConsumerInterface
{
    public function consume(callable $callback, ConsumerParametersInterface $consumer);
}