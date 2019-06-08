<?php


namespace App\Modules\Analytics;


use App\Library;

/**
 * Class QueueEntity
 * @package App\Modules\Analytics
 * @method static self getInstance()
 */
class QueueEntity extends Library\Singleton
{
    const QUEUE_NAME = 'analyticsQueue';
    const EXCHANGE_NAME = 'analyticsChange';

    /**
     * @var Library\Queue\RabbitMQ
     */
    private $rabbit;

    /**
     * @var Library\Queue\RabbitMQ\ConsumerParameters
     */
    private $consumerParameters;

    /**
     * QueueEntity constructor.
     */
    protected function __construct()
    {
        $this->rabbit = new Library\Queue\RabbitMQ(self::QUEUE_NAME, self::EXCHANGE_NAME);
        $this->rabbit->queue()->durable(true);
        $this->rabbit->bind();
        $this->consumerParameters = new Library\Queue\RabbitMQ\ConsumerParameters();
        $this->consumerParameters->setConsumerTag('AnalyticsConsumer');
        parent::__construct();
    }

    /**
     * @return Library\Queue\RabbitMQ
     */
    public function getRabbit()
    {
        return $this->rabbit;
    }

    /**
     * @return Library\Queue\RabbitMQ\ConsumerParameters
     */
    public function getConsumerParameters()
    {
        return $this->consumerParameters;
    }
}