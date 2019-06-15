<?php


namespace App\Integrations\Messagers\Telegram;

use App\Library\Utilities;

/**
 * Class Callback
 * @package App\Integrations\Messagers\Telegram
 * @method static self staticHandle()
 */
class Callback
{
    /**
     * @var int
     */
    public $updateId;

    /**
     * @var Objects\Message
     */
    public $message;

    /**
     * @var array
     */
    private $content;

    public function handle()
    {
        $content = file_get_contents('php://input');

        try {
            $this->content = Utilities\Json::decode($content);
        }
        catch (\Exception $e) {
            throw new Exception('Unknown data type in Callback');
        }

        $this->parse($this->content);
    }

    /**
     * @param array $content
     */
    public function parse(array $content)
    {
        if (array_key_exists('update_id', $content)) {
            $this->updateId = $content['update_id'];
        }

        if (array_key_exists('message', $content)) {
            $this->message = $this->parseMessage($content['message']);
        }
    }

    /**
     * @param array $array
     * @return Objects\Message
     */
    private function parseMessage(array $array)
    {
        $message = new Objects\Message();
        $message->parse($array);
        return $message;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Callback
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name === 'staticHandle') {
            $thisObject = new self();
            $thisObject->handle();
            return $thisObject;
        }

        throw new Exception('Undefined method');
    }
}