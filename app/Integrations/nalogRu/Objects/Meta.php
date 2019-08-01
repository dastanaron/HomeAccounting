<?php


namespace App\Integrations\nalogRu\Objects;

use App\Integrations\nalogRu\Library;

class Meta implements \JsonSerializable
{
    const ARRAY_KEY_PHONE    = 'phone';
    const ARRAY_KEY_EMAIL    = 'email';
    const ARRAY_KEY_SMS_CODE = 'smsCode';
    const ARRAY_KEY_NAME     = 'name';

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
            self::ARRAY_KEY_EMAIL    => $this->email,
            self::ARRAY_KEY_NAME     => $this->name,
            self::ARRAY_KEY_PHONE    => $this->phone,
            self::ARRAY_KEY_SMS_CODE => $this->smsCode,
        ];
    }

    /**
     * @param array $array
     * @return Meta
     * @throws Library\Exception
     */
    public static function parseFromArray(array $array): Meta
    {
        if (!self::validateMetaArray($array)) {
            throw new Library\Exception('meta array is invalid');
        }

        $meta = new self();
        $meta->name    = $array[self::ARRAY_KEY_NAME];
        $meta->email   = $array[self::ARRAY_KEY_EMAIL];
        $meta->smsCode = $array[self::ARRAY_KEY_SMS_CODE];
        $meta->phone   = $array[self::ARRAY_KEY_PHONE];
        return $meta;
    }

    /**
     * @param array $meta
     * @return bool
     */
    public static function validateMetaArray(array $meta): bool
    {
        return array_key_exists(self::ARRAY_KEY_NAME, $meta)
            && array_key_exists(self::ARRAY_KEY_EMAIL, $meta)
            && array_key_exists(self::ARRAY_KEY_SMS_CODE, $meta)
            && (array_key_exists(self::ARRAY_KEY_PHONE, $meta) && self::validatePhone($meta[self::ARRAY_KEY_PHONE]));
    }

    /**
     * @param string $phoneNumber
     * @return bool
     */
    public static function validatePhone(string $phoneNumber): bool
    {
        return preg_match('#^\+7[0-9]{10}$#', $phoneNumber);
    }
}