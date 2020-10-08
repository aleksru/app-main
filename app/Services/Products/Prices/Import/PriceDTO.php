<?php


namespace App\Services\Products\Prices\Import;


class PriceDTO
{
    /**
     * @var string
     */
    protected $productArticle;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var float|null
     */
    protected $priceSpecial;

    /**
     * PriceDTO constructor.
     * @param string $productArticle
     * @param float $price
     * @param float|null $priceSpecial
     */
    public function __construct(string $productArticle, float $price, ?float $priceSpecial)
    {
        $this->productArticle = $productArticle;
        $this->price = $price;
        $this->priceSpecial = $priceSpecial;
    }

    /**
     * @return string
     */
    public function getProductArticle(): string
    {
        return $this->productArticle;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return float|null
     */
    public function getPriceSpecial(): ?float
    {
        return $this->priceSpecial;
    }
}
