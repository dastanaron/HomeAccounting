<?php


namespace App\Integrations\nalogRu\Library;

use GuzzleHttp;

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
     * @return string
     */
    public function request(string $command, string $method = self::METHOD_POST)
    {
        return $this->client->request($method, $command, $this->parameters->getParameters())->getBody()->getContents();
    }
}