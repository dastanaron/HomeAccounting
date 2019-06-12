<?php


namespace App\Integrations\Messagers\Telegram\Objects;

use App\Integrations\Messagers\Telegram;

class Message
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Message\From
     */
    public $from;

    /**
     * @var Message\AbstractChat
     */
    public $chat;

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @var string
     */
    public $text;

    /**
     * @var Message
     */
    public $replyToMessage;

    /**
     * @var array
     */
    public $entities;

    /**
     * @var Message\Document
     */
    public $document;

    /**
     * @param array $array
     */
    public function parse(array $array) : void
    {
        foreach ($array as $key => $value) {
            $camelCaseKey = camel_case($key);
            switch ($camelCaseKey) {
                case 'messageId':
                    $this->id = $value;
                    break;
                case 'from':
                    $this->from = new Message\From();
                    $this->from->parse($value);
                    break;
                case 'chat':
                    $parser = new Message\ChatParser();
                    $this->chat = $parser->parse($value);
                    break;
                case 'date':
                    $this->date = new \DateTime();
                    $this->date->setTimestamp($value);
                    break;
                case 'replyToMessage':
                    $this->replyToMessage = (new self());
                    $this->replyToMessage->parse($value);
                    break;
                case 'document':
                    $this->document = new Message\Document();
                    $this->document->parse($value);
                    break;
                default:
                    $this->$camelCaseKey = $value;
                    break;
            }
        }
    }

    /**
     * @return bool
     */
    public function isCommand() : bool
    {
        return (bool) preg_match('#^\/(.*)#', $this->text);
    }

    /**
     * @return Command
     * @throws Telegram\Exception
     */
    public function command() : Command
    {
        if ($this->isCommand()) {
            $command = new Command();
            $command->parse($this->text);
            return $command;
        }
        else {
            throw new Telegram\Exception('This message is not command');
        }
    }
}