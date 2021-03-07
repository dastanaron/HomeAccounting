<?php
declare(strict_types=1);

namespace App\Modules\Consumers;

use App\Library\Queue;
use PhpAmqpLib;
use Illuminate\{
    Support\Facades
};

/**
 * Class AbstractRabbitMQConsumer
 * @package App\Modules\Consumers
 */
abstract class AbstractRabbitMQConsumer
{

    /**
     * @var Queue\RabbitMQ
     */
    protected $rabbit;

    /**
     * @var Queue\RabbitMQ\ConsumerParameters
     */
    protected $consumerParams;

    /**
     * run consumer
     */
    public function run()
    {
        try {
            $this->rabbit->consume(function (PhpAmqpLib\Message\AMQPMessage $messageOriginal) {
                $message = new Queue\RabbitMQ\Message();
                $message->setAmqpMessage($messageOriginal);
                $this->processEnvelope($message);
            }, $this->consumerParams);

            while (count($this->rabbit->channel()->callbacks)) {
                $this->rabbit->channel()->wait();
            }
        }
        catch (\Exception $e) {
            \Log::critical($e->getMessage(), $e->getTraceAsString());
        }

    }

    /**
     * AbstractRabbitMQConsumer constructor.
     */
    public function __construct()
    {
        $this->rabbit = $this->buildRabbitMQObject();
        $this->consumerParams = $this->buildConsumerParameters();
    }

    /**
     * @param Queue\RabbitMQ\Message $message
     * @return mixed
     */
    abstract function processEnvelope(Queue\RabbitMQ\Message $message);

    /**
     * @return Queue\RabbitMQ
     */
    abstract protected function buildRabbitMQObject();

    /**
     * @return Queue\RabbitMQ\ConsumerParameters
     */
    abstract protected function buildConsumerParameters();

    /**
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @param \PhpAmqpLib\Connection\AbstractConnection $connection
     */
    public static function shutdown(PhpAmqpLib\Channel\AMQPChannel $channel, PhpAmqpLib\Connection\AbstractConnection $connection)
    {
        $channel->close();
        $connection->close();
    }

    public static function errorLog(string $message): void
    {
        Facades\Log::error($message);
    }
}
