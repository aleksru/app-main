<?php


namespace App;


class OrderProductTextData
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $article;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var float
     */
    private $price;

    /**
     * OrderProductTextData constructor.
     * @param string $name
     * @param string $article
     * @param int $quantity
     * @param float $price
     */
    public function __construct(string $name, string $article, int $quantity, float $price)
    {
        $this->name = $name;
        $this->article = $article;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return $this->article;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity >= 1 ? $this->quantity : 1;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}