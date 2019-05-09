<?php


namespace App\Library\Utilities;


class Json
{
    public static function encode($value, $options = 0, $depth = 512)
    {
        return json_encode($value, $options, $depth);
    }

    public static function decode($json, $assoc = true, $depth = 512, $options = 0)
    {
        return json_decode($json, $assoc, $depth, $options);
    }
}