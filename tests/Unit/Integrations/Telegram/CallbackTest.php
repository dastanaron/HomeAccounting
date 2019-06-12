<?php

namespace Tests\Unit;

use App\Integrations\Messagers\Telegram;
use Tests\TestCase;
use App\Library\Utilities;

class CallbackTest extends TestCase
{
    public function testSimpleMessage()
    {
        $content = Utilities\Json::decode(file_get_contents('tests/Unit/Integrations/Telegram/Json/simpleMessage.json'));
        $telegramCallback = new Telegram\Callback();
        $telegramCallback->parse($content);

        $this->assertSame(521982041, $telegramCallback->updateId);
        $this->assertSame(394, $telegramCallback->message->id);
        $this->assertFalse($telegramCallback->message->from->isBot);
        $this->assertInstanceOf(Telegram\Objects\Message\PrivateChat::class, $telegramCallback->message->chat);
        $this->assertInstanceOf(\DateTime::class, $telegramCallback->message->date);
        $this->assertSame('Тест', $telegramCallback->message->text);
    }

    public function testCommandMessage()
    {
        $content = Utilities\Json::decode(file_get_contents('tests/Unit/Integrations/Telegram/Json/commandMessage.json'));
        $telegramCallback = new Telegram\Callback();
        $telegramCallback->parse($content);

        $this->assertTrue($telegramCallback->message->isCommand());
        $this->assertSame(521982043, $telegramCallback->updateId);
        $this->assertSame(279795272, $telegramCallback->message->from->id);
        $this->assertInstanceOf(Telegram\Objects\Message\PrivateChat::class, $telegramCallback->message->chat);
        $this->assertInstanceOf(\DateTime::class, $telegramCallback->message->date);
    }

    public function testAnswerIntoGroup()
    {
        $content = Utilities\Json::decode(file_get_contents('tests/Unit/Integrations/Telegram/Json/answerToBotInGroup.json'));
        $telegramCallback = new Telegram\Callback();
        $telegramCallback->parse($content);

        $this->assertSame(521982048, $telegramCallback->updateId);
        $this->assertSame(401, $telegramCallback->message->id);
        $this->assertInstanceOf(Telegram\Objects\Message\GroupChat::class, $telegramCallback->message->chat);
        $this->assertInstanceOf(\DateTime::class, $telegramCallback->message->date);
        $this->assertNotNull($telegramCallback->message->replyToMessage);
        $this->assertInstanceOf(Telegram\Objects\Message::class, $telegramCallback->message->replyToMessage);
        $this->assertSame(387, $telegramCallback->message->replyToMessage->id);
    }

    public function testMessageWithFile()
    {
        $content = Utilities\Json::decode(file_get_contents('tests/Unit/Integrations/Telegram/Json/messageWithFile.json'));
        $telegramCallback = new Telegram\Callback();
        $telegramCallback->parse($content);

        $this->assertSame(521982047, $telegramCallback->updateId);
        $this->assertSame(400, $telegramCallback->message->id);
        $this->assertInstanceOf(Telegram\Objects\Message\PrivateChat::class, $telegramCallback->message->chat);
        $this->assertInstanceOf(\DateTime::class, $telegramCallback->message->date);
        $this->assertInstanceOf(Telegram\Objects\Message\Document::class, $telegramCallback->message->document);
        $this->assertSame('05.06.19 22.26.11_receipt_share.json', $telegramCallback->message->document->fileName);
        $this->assertSame('BQADAgADSQQAAoqPwEsEwufRjeOMbQI', $telegramCallback->message->document->fileId);
    }
}
