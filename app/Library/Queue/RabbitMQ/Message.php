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
class Message extends PhpAmqpLib\Message\AMQPMessage implements Queue\Interfaces\MessageInterface
{
    /**
     * @param string $array
     * @return PhpAmqpLib\Message\AMQPMessage
     * @throws Utilities\Exceptions\EncodingException
     */
    public function setBody($array)
    {
        $body = Utilities\Json::encode($array);
        return parent::setBody($body);
    }

    /**
     * @return mixed|string
     * @throws Utilities\Exceptions\DecodingException
     */
    public function getBody()
    {
        return Utilities\Json::decode($this->body, true);
    }

    /**
     * @return mixed
     * @throws Exceptions\BaseException
     */
    public function getDeliveryTag()
    {
        if (array_key_exists('delivery_tag', $this->delivery_info)) {
            return $this->delivery_info['delivery_tag'];
        }
        else {
            throw new Exceptions\BaseException('delivery tag does not exist in the message');
        }
    }
}