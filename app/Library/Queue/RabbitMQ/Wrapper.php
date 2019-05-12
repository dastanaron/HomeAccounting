<?php


namespace App\Library\Queue\RabbitMQ;

use App\Library;
use App\Library\Utilities;
use PhpAmqpLib;

class Wrapper extends Library\Singleton
{
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @var PhpAmqpLib\Connection\AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var PhpAmqpLib\Channel\AMQPChannel
     */
    protected $channel;

    /**
     * MessagePush constructor.
     */
    protected function __construct()
    {
        $this->config = config('queue.connections.rabbitmq');

        $this->connection = $this->createConnection();

        $this->channel = $this->connection->channel();

        parent::__construct();
    }

    /**
     * @param QueueDeclarator $queueDeclarator
     * @return $this
     */
    public function queueDeclare(QueueDeclarator $queueDeclarator)
    {
        $this->channel->queue_declare(
            $queueDeclarator->queueName,
            $queueDeclarator->passive,
            $queueDeclarator->durable,
            $queueDeclarator->exclusive,
            $queueDeclarator->autoDelete,
            $queueDeclarator->noWait,
            $queueDeclarator->arguments,
            $queueDeclarator->ticket
        );
        return $this;
    }

    /**
     * @param ExchangeDeclarator $exchangeDeclarator
     * @return $this
     */
    public function exchangeDeclare(ExchangeDeclarator $exchangeDeclarator)
    {
        $this->channel->exchange_declare(
            $exchangeDeclarator->exchangeName,
            $exchangeDeclarator->type,
            $exchangeDeclarator->passive,
            $exchangeDeclarator->durable,
            $exchangeDeclarator->autoDelete,
            $exchangeDeclarator->internal,
            $exchangeDeclarator->noWait,
            $exchangeDeclarator->arguments,
            $exchangeDeclarator->ticket
        );
        return $this;
    }

    /**
     * @param QueueDeclarator $queueDeclarator
     * @param ExchangeDeclarator $exchangeDeclarator
     * @param null $routingKey
     * @return $this
     */
    public function bind(QueueDeclarator $queueDeclarator, ExchangeDeclarator $exchangeDeclarator, $routingKey = null)
    {
        $this->queueDeclare($queueDeclarator);
        $this->exchangeDeclare($exchangeDeclarator);

        if($routingKey !== null && Utilities\TypesValidator::isString($routingKey)) {
            $preparedRoutingKey = $routingKey;
        }
        else {
            $preparedRoutingKey = $queueDeclarator->queueName;
        }

        $this->channel->queue_bind($queueDeclarator->queueName, $exchangeDeclarator, $preparedRoutingKey);
        return $this;
    }

    /**
     * @return PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * MessagePush destructor
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @return PhpAmqpLib\Connection\AMQPStreamConnection
     */
    protected function createConnection()
    {
        return new PhpAmqpLib\Connection\AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['login'],
            $this->config['password'],
            $this->config['vhost']
        );
    }
}