<?php


namespace App\Integrations\nalogRu\Library;


use App\Integrations\nalogRu\Objects;

class BarcodeParser
{
    /**
     * @param string $barcodeString
     * @return Objects\BarcodeInfoAboutCheck
     */
    public function simpleParse(string $barcodeString)
    {
        $barcodeInfo = new Objects\BarcodeInfoAboutCheck();
        $params = $this->parseBarcodeString($barcodeString);
        $this->fillObject($params, $barcodeInfo);
        return $barcodeInfo;
    }

    /**
     * @param $array
     * @param Objects\BarcodeInfoAboutCheck $object
     */
    private function fillObject($array, Objects\BarcodeInfoAboutCheck $object)
    {
        foreach ($array as $key => $value) {
            switch ($key) {
                case 't':
                    $object->date = new \DateTime($value, new \DateTimeZone('UTC'));
                    break;
                case 's':
                    $object->sum = (float) $value;
                    break;
                case 'fn':
                    $object->fiscalNumber = (string) $value;
                    break;
                case 'i':
                    $object->fiscalDocument = (string) $value;
                    break;
                case 'fp':
                    $object->fiscalSign = (string) $value;
                    break;
                case 'n':
                    $object->checkType = (string) $value;
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param string $barcodeString
     * @return array
     */
    private function parseBarcodeString(string $barcodeString)
    {
        $array = [];
        $params = explode('&', $barcodeString);

        foreach ($params as $param) {
            $data = explode('=', $param);
            $key = trim($data[0]);
            $value = trim($data[1]);
            $array[$key] = $value;
        }
        return $array;
    }
}