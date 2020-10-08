<?php


namespace App\Services\Products\Import\Data;


class ProductDTO
{
    /**
     * @var string
     */
    protected $article;

    /**
     * @var string
     */
    protected $productName;

    /**
     * ProductDTO constructor.
     * @param string $article
     * @param string $productName
     */
    public function __construct(string $article, string $productName)
    {
        $this->article = $article;
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return $this->article;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }
}
