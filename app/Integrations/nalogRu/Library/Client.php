<?php


namespace App\Integrations\nalogRu\Library;

use GuzzleHttp;
use App\Library\Utilities;

class Client
{
    const USER_AGENT = 'Mozilla/5.0 (Linux; U; Android 2.2) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * @var RequestParametersBuilder
     */
    private $parameters;

    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Client constructor.
     * @param string $baseUri
     */
    public function __construct(string $baseUri)
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => $baseUri
        ]);
        $this->parameters = new RequestParametersBuilder();
        $this->parameters->headers([
            'User-Agent' => self::USER_AGENT,
            'Accept'     => 'application/json',
        ]);
    }

    /**
     * @return RequestParametersBuilder
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $command
     * @param string $method
     * @return Answer
     */
    public function request(string $command, string $method = self::METHOD_POST)
    {
        try {
            $response = $this->client->request($method, $command, $this->parameters->getParameters());
            return new Answer((int) $response->getStatusCode(), $response->getReasonPhrase(), $response->getBody()->getContents());
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return $this->errorAnswer($e);
        }
    }

    /**
     * @param GuzzleHttp\Exception\ClientException $e
     * @return Answer
     */
    private function errorAnswer(GuzzleHttp\Exception\ClientException $e)
    {
        return new Answer((int) $e->getCode(), $e->getMessage(), $e->getResponse()->getBody());
    }
}