<?php


namespace App\Library\Queue\Interfaces;


interface MessageInterface
{
    public function setBody($data);
    public function getBody();
}