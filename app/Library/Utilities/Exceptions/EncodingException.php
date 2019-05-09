<?php


namespace App\Library\Utilities\Exceptions;

use Throwable;

class EncodingException extends UtilitiesAbstractException
{
    const DEFAULT_ERROR_MESSAGE = 'cannot encode string';

    /**
     * DecodingException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable $previous
     */
    public function __construct($message = self::DEFAULT_ERROR_MESSAGE, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}