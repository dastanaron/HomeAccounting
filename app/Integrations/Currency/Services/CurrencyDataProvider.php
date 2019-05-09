<?php

namespace App\Integrations\Currency\Services;

use App\Library\Exceptions;

class CurrencyDataProvider
{
    /**
     * @var string
     */
    public $CBRFID;

    /**
     * @var int
     */
    public $numCode;

    /**
     * @var string
     */
    public $charCode;

    /**
     * @var int
     */
    public $nominal;

    /**
     * @var string
     */
    public $name;

    /**
     * @var float
     */
    public $value;

    /**
     * @param \SimpleXMLElement $element
     * @return CurrencyDataProvider
     * @throws Exceptions\BaseException
     * @throws \ReflectionException
     */
    public function buildProviderByXml(\SimpleXMLElement $element) : CurrencyDataProvider
    {
        $this->CBRFID = (string) $element->attributes()->ID;
        $this->numCode = (int) $element->NumCode;
        $this->charCode = (string) $element->CharCode;
        $this->nominal = (int) $element->Nominal;
        $this->name = (string) $element->Name;
        $this->value = (float) str_replace(',', '.', $element->Value);

        if($this->validate())
        {
            return $this;
        }
        else
        {
            throw new Exceptions\BaseException('parse XmlElement CBRF error');
        }

    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    public function validate()
    {
        $reflection = new \ReflectionClass(__CLASS__);

        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            if($property->isPublic()) {

                $propertyName = $property->getName();

               if(empty($this->$propertyName))
               {
                    return false;
               }

            }
        }

        return true;
    }

}