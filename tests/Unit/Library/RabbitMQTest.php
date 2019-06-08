<?php

namespace Tests\Unit\Library;

use Tests\TestCase;
use App\Library\Queue;

class RabbitMQTest extends TestCase
{

    /**
     * @var Queue\RabbitMQ
     */
    protected $rabbit;

    protected function setUp()
    {
        parent::setUp();
        $this->rabbit = new Queue\RabbitMQ('testQueue', 'testExchange');

        $this->rabbit->bind();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDeclare()
    {
        try {
            $rabbit = new Queue\RabbitMQ('testQueue', 'testExchange');

            $rabbit->bind();
        }
        catch (\Exception $e) {
            $this->assertTrue(false, 'Cannot create queue and exchange');
        }

        $this->assertTrue(true);
    }

    /**
     * @dataProvider messagesDataProvider
     * @param $message
     * @param $answer
     */
    public function testPushMessage($message, $answer)
    {

        $queueWasCleared = $this->rabbit->clearQueue();

        $this->assertTrue($queueWasCleared, 'Cannot clear test queue');

        $preparedMessage = new Queue\RabbitMQ\Message($message);

        try {
            $this->rabbit->push($preparedMessage);
        }
        catch (\Exception $e) {
            $this->assertTrue(false, 'Error push message to queue');
        }

        $pulledMessage = $this->rabbit->pull(true);

        $this->assertInstanceOf(Queue\RabbitMQ\Message::class, $pulledMessage,'Pulled message is an object of ' . get_class($pulledMessage) . ' class');

        $this->assertSame($answer, $pulledMessage->getBody());

    }

    public function messagesDataProvider()
    {
        return [
            ['stringMessage', 'stringMessage'],
            [['array' => 'message'], ['array' => 'message']],
        ];
    }

    public function testDeleteQueue()
    {
        $this->assertTrue($this->rabbit->deleteQueue(), 'cannot delete queue');
    }
}
