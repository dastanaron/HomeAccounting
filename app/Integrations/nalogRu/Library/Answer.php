<?php


namespace App\Integrations\nalogRu\Library;

use App\Library;

class Answer
{
    /**
     * @var int
     */
    protected $code;
    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $response;

    /**
     * @var string[]
     */
    protected $errors;

    public function __construct(int $code, string $message, string $response)
    {
        $this->code = $code;
        $this->message = $message;

        if (Library\Utilities\Json::isValid($response)) {
            $this->response = Library\Utilities\Json::decode($response);
        }
        else {
            $this->errors['response'] = 'incorrect answer type';
            $this->response = [$response];
        }
    }

    /**
     * @return int
     */
    public function code(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function response(): array
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}