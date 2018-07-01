<?php

namespace App\Modules\Import\Ofx;


use App\Modules\Import\ImportFileInterface;

class OfxParser implements ImportFileInterface
{

    /**
     * @var string
     */
    protected static $inputCharset = 'UTF-8';
    /**
     * @var string
     */
    protected static $outputCharset = 'UTF-8';
    /**
     * @var string
     */
    protected $fileContent = '';

    /**
     * @return OfxParser
     */
    public static function getInstance(): OfxParser
    {
        return new self;
    }

    /**
     * @param string $filePath
     * @return OfxObject
     */
    public function openFile(string $filePath): OfxObject
    {
        $fileContent = file_get_contents($filePath);

        $fileContent = $this->convertCharset($fileContent);

        $this->fileContent = $fileContent;

        return new OfxObject($this->fileContent);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function convertCharset(string $string): string
    {
        if(self::$inputCharset === self::$outputCharset) {
            return $string;
        }
        else {
            return iconv(self::$inputCharset, self::$outputCharset, $string);
        }
    }

    /**
     * @param string $inputCharset
     */
    public static function setInputCharset(string $inputCharset): void
    {
        self::$inputCharset = $inputCharset;
    }

    /**
     * @param string $outputCharset
     */
    public static function setOutputCharset(string $outputCharset): void
    {
        self::$outputCharset = $outputCharset;
    }



}