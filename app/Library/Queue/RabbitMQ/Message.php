<?php


namespace App\Library\Queue\RabbitMQ;

use App\Library\Queue;
use PhpAmqpLib;
use App\Library\Exceptions;
use App\Library\Utilities;

/**
 * Class Message
 * @package App\Library\Queue\RabbitMQ
 */
class Message implements Queue\Interfaces\MessageInterface
{

    /**
     * @var MessageParameters
     */
    private $parameters;

    /**
     * @var PhpAmqpLib\Message\AMQPMessage
     */
    private $amqpMessage;

    /**
     * @var string
     */
    private $body;

    /**
     * @param array|string|object $body
     * @param array $properties
     */
    public function __construct($body = '', $properties = array())
    {
        $this->setBody($body);
        $this->amqpMessage = new PhpAmqpLib\Message\AMQPMessage($this->body, $properties);
        $this->parameters = new MessageParameters();
    }

    /**
     * @param PhpAmqpLib\Message\AMQPMessage $message
     */
    public function setAmqpMessage(PhpAmqpLib\Message\AMQPMessage $message)
    {
        $this->amqpMessage = $message;
    }

    /**
     * @return PhpAmqpLib\Message\AMQPMessage
     */
    public function getAMQPMessage()
    {
        return $this->amqpMessage;
    }

    /**
     * @return mixed|PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel()
    {
        return $this->amqpMessage->get('channel');
    }

    /**
     * @param string|array
     */
    public function setBody($body)
    {
        $this->body = Utilities\Json::encode($body);
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return Utilities\Json::decode($this->amqpMessage->getBody(), true);
    }

    /**
     * @return MessageParameters
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * @return mixed
     * @throws Exceptions\BaseException
     */
    public function getDeliveryTag()
    {
        if (array_key_exists('delivery_tag', $this->amqpMessage->delivery_info)) {
            return $this->amqpMessage->delivery_info['delivery_tag'];
        }
        else {
            throw new Exceptions\BaseException('delivery tag does not exist in the message');
        }
    }
}