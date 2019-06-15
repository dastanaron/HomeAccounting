<?php


namespace App\Integrations\Messagers\Telegram\Objects\Message;

use App\Integrations\Messagers\Telegram\Objects;

abstract class AbstractChat
{
    use Objects\camelCaseParserFromArray;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $type;
}