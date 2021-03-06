<?php


namespace App\Integrations\nalogRu\Objects;


class BarcodeInfoAboutCheck
{
/*
------------------------------------------------------------------------------------------------------------------------------------------------
1. Номер ФН (Фискальный Номер) — 16-значный номер. Например 8710000100518392
2. Номер ФД (Фискальный документ) — до 10 знаков. Например 54812
3. Номер ФПД (Фискальный Признак Документа, также известный как ФП) — до 10 знаков. Например 3522207165
4. Вид кассового чека. В чеке помечается как n=1 (приход) и n=2 (возврат прихода)
5. Дата — дата с чека. Формат может отличаться. Я пробовал переворачивать дату (т.е. 17-05-2018), ставить вместо Т пробел, удалять секунды
6. Сумма — сумма с чека в копейках

------------------------------------------------------------------------------------------------------------------------------------------------
Пример сканированной строки:
t=20190613T132300&s=524.39&fn=9289000100393237&i=20509&fp=2249765769&n=1
------------------------------------------------------------------------------------------------------------------------------------------------
*/
    /**
     * @onCheck fn
     * @example "fn":"9289000100393237"
     * @var string
     */
    public $fiscalNumber;

    /**
     * @onCheck i
     * @example "i":"20509"
     * @var string
     */
    public $fiscalDocument;

    /**
     * @onCheck fp
     * @example "fp":"2249765769"
     * @var string
     */
    public $fiscalSign;

    /**
     * @onCheck n
     * @example "n":"1"
     * @var string
     */
    public $checkType;

    /**
     * @onCheck t
     * @example "t":"20190613T132300"
     * @var \DateTime
     */
    public $date;

    /**
     * @onCheck s
     * @example "s":"524.39"
     * @var float
     */
    public $sum;

    /**
     * @return bool
     */
    public function isValid()
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->name;
            if (empty($this->$propertyName)) {
                return false;
            }
        }

        return true;
    }
}