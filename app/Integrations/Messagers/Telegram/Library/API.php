<?php


namespace App\Integrations\Messagers\Telegram\Library;

use App\Library;

/**
 * Class API
 * @package App\Integrations\Messagers\Telegram\Library
 * @method API getInstance()
 */
class API extends Library\Singleton
{
    protected $url = 'https://api.telegram.org/';

    protected $bot = 'bot';

    /**
     * API constructor.
     */
    protected function __construct()
    {
        $this->bot .= config('integrations.telegram.bot_key', null);
        parent::__construct();
    }
}