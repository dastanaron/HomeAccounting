<?php


namespace App\Integrations\Messagers\Telegram\Objects\Message;

use App\Integrations\Messagers\Telegram\Objects;

class From
{
    use Objects\camelCaseParserFromArray;

    /**
     * @var int
     */
    public $id;

    /**
     * @var bool
     */
    public $isBot;

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
    public $languageCode;
}