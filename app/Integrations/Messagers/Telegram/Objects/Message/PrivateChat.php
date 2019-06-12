<?php


namespace App\Integrations\Messagers\Telegram\Objects\Message;


class PrivateChat extends AbstractChat
{
    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $type = "private";

}