<?php


namespace Tests\Unit\Components\PA\CRUD\Mock;

use Illuminate\Http;

/**
 * Class Request
 * @package Tests\Unit\Components\PA\CRUD\Mock
 */
class BillsRequest
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $deadline;
    /**
     * @var string
     */
    private $comment;
    /**
     * @var string
     */
    private $sum;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var string
     */
    private $bill_id;

    /**
     * @param string $attribute
     * @return string|null
     */
    public function input(string $attribute): ?string
    {
        return $this->$attribute;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $deadline
     */
    public function setDeadline(string $deadline): void
    {
        $this->deadline = $deadline;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @param string $sum
     */
    public function setSum(string $sum): void
    {
        $this->sum = $sum;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @param string $bill_id
     */
    public function setBillId(string $bill_id): void
    {
        $this->bill_id = $bill_id;
    }
}