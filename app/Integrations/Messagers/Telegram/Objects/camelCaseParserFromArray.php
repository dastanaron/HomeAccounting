<?php


namespace App\Integrations\Messagers\Telegram\Objects;


trait camelCaseParserFromArray
{
    function parse(array $array)
    {
        foreach ($array as $key => $value) {
            $camelCaseKey = camel_case($key);
            if (property_exists($this, $camelCaseKey)) {
                $this->$camelCaseKey = $value;
            }
        }
    }
}