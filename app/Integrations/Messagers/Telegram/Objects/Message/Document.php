<?php


namespace App\Integrations\Messagers\Telegram\Objects\Message;

use App\Integrations\Messagers\Telegram\Objects;

class Document
{
    use Objects\camelCaseParserFromArray;

    /**
     * @var string
     */
    public $fileName;

    /**
     * @var string
     */
    public $mimeType;

    /**
     * @var string
     */
    public $fileId;

    /**
     * @var string
     */
    public $fileSize;
}