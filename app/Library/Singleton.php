<?php


namespace App\Library;

use App\Library\Exceptions;

abstract class Singleton
{
    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * Singleton constructor.
     */
    protected function __construct() { }

    /**
     * forbiddance to clone
     */
    protected function __clone() { }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new Exceptions\BaseException("Cannot unserialize a singleton.");
    }

    /**
     * @return Singleton
     */
    public static function getInstance(): Singleton
    {
        $cls = static::class;
        if (!isset(static::$instances[$cls])) {
            static::$instances[$cls] = new static;
        }

        return static::$instances[$cls];
    }
}
