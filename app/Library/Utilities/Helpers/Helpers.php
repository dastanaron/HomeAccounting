<?php

declare(strict_types=1);

namespace App\Library\Utilities\Helpers;

use App\Library\Singleton;

/**
 * Class Helpers
 * @package App\Library\Utilities\Helpers
 */
class Helpers extends Singleton
{

    /**
     * @var MoneyHelper
     */
    private $money;

    protected function __construct()
    {
        $this->money = new MoneyHelper();
        parent::__construct();
    }

    /**
     * @return MoneyHelper
     */
    public function money(): MoneyHelper
    {
        return $this->money;
    }

}