<?php


namespace App\Integrations\nalogRu\Library;


class RequestParametersBuilder
{
    /**
     * @see https://www.ietf.org/rfc/rfc2069.txt
     */
    const DIGEST_AUTHENTICATION = 'digest';

    /**
     * Microsoft NTLM authentication
     * @see https://docs.microsoft.com/ru-ru/windows/desktop/SecAuthN/microsoft-ntlm
     */
    const NTLM_AUTHENTICATION = 'ntlm';

    private $parameters;

    public function getParameters()
    {
        return $this->parameters;
    }

    public function json(array $array)
    {
        $this->parameters['json'] = $array;
        return $this;
    }

    public function auth($userName, $password, $type = null)
    {
        switch ($type) {
            case self::DIGEST_AUTHENTICATION:
                $this->parameters['auth'] = [$userName, $password, self::DIGEST_AUTHENTICATION];
                break;
            case self::NTLM_AUTHENTICATION:
                $this->parameters['auth'] = [$userName, $password, self::NTLM_AUTHENTICATION];
                break;
            default:
                $this->parameters['auth'] = [$userName, $password];
        }
        return $this;
    }

    public function headers(array $array)
    {
        $this->parameters['headers'] = $array;
        return $this;
    }
}