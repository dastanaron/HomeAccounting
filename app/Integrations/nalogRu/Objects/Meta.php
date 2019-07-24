<?php


namespace App\Integrations\nalogRu\Objects;


class Meta implements \JsonSerializable
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var int
     */
    public $smsCode;

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'email'   => $this->email,
            'name'    => $this->name,
            'phone'   => $this->phone,
            'smsCode' => $this->smsCode,
        ];
    }
}