<?php


namespace App\Integrations\Messagers\Telegram\Objects;


class Command
{
    /**
     * @var string
     */
    public $name;

    /**
     * @param string $text
     * @return Command
     */
    public function parse(string $text) : Command
    {
        //todo: implement it later

        return $this;
    }
}