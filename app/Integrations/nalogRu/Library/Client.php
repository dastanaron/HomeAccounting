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
     * @return string
     */
    public function request(string $command, string $method = self::METHOD_POST)
    {
        try {
            $response = $this->client->request($method, $command, $this->parameters->getParameters());
            return $response->getBody()->getContents();
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            return $this->errorString($e);
        }
    }

    /**
     * @param GuzzleHttp\Exception\ClientException $e
     * @return string
     */
    private function errorString(GuzzleHttp\Exception\ClientException $e)
    {
        $data = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'response' => $e->getResponse()->getBody(),
        ];

        return Utilities\Json::encode($data);
    }
}