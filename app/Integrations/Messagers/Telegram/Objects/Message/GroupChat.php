<?php


namespace App\Integrations\Messagers\Telegram\Objects\Message;


class GroupChat extends AbstractChat
{
    /**
     * @var
     */
    public $title;

    /**
     * @var string
     */
    public $type = "group";

    /**
     * @var
     */
    public $allMembersAreAdministrators;
}