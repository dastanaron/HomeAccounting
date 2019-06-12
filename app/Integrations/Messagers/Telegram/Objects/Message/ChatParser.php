<?php


namespace App\Integrations\Messagers\Telegram\Objects\Message;


class ChatParser
{
    /**
     * @param array $array
     * @return AbstractChat|null
     */
    public function parse(array $array)
    {
        if (array_key_exists('type', $array)) {
            $type = $array['type'];

            switch ($type) {
                case 'group':
                    $object = new GroupChat();
                    $object->parse($array);
                    break;
                case 'private':
                    $object = new PrivateChat();
                    $object->parse($array);
                    break;
                default:
                    $object = null;
                    break;
            }

            return $object;
        }
        else {
            return null;
        }
    }
}