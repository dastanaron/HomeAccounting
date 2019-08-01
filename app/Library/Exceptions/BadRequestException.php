<?php


namespace App\Library\Exceptions;

use Throwable;
use Illuminate\Http;

class BadRequestException extends BaseException
{
    /**
     * @var array WW
     */
    private $requestData;

    /**
     * BadRequestException constructor.
     * @param Http\Request $request
     * @param $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Http\Request $request, string $message = 'Request parameters are invalid', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->requestData = $request->toArray();
    }
}