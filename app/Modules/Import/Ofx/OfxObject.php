<?php

namespace App\Modules\Import\Ofx;


class OfxObject
{

    /**
     * @var string
     */
    private $content = '';
    /**
     * @var \SimpleXMLElement
     */
    private $xmlObject;

    /**
     * OfxObject constructor.
     * @param string $xml
     */
    public function __construct(string $xml)
    {
        $this->content = $xml;

        $this->xmlObject = new \SimpleXMLElement($this->content);
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getObject(): \SimpleXMLElement
    {
        return $this->xmlObject;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }



}