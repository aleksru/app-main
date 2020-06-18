<?php


namespace App\Builders;


use App\Order;
use App\OrderProductTextData;
use App\Product;

class OrderManager
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * OrderManager constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param Product $product
     * @param float $price
     */
    public function addRealization(Product $product, float $price)
    {
        if($this->order->getCountRealizations() < config('realization.max_order_realizations')){
            $this->order->realizations()->create([
                'quantity'     => 1,
                'price'        => ($this->order->store && ! $this->order->store->is_disable_api_price) ? $price : 0,
                'product_id'   => $product->id,
                'product_type' => $product->type
            ]);
        }
    }

    /**
     * @param Product $product
     * @param float $price
     * @param int $quantity
     */
    public function addRealizations(Product $product, float $price, int $quantity)
    {
        for ($i = 0; $i < $quantity; $i++){
            $this->addRealization($product, $price);
        }
    }

    /**
     * @return void
     */
    public function addRealizationsFromProductsText()
    {
        $productsTextArray = $this->order->getProductTextDataArray();
        foreach ($productsTextArray as $item){
            $this->addRealizationFromOrderProductTextData($item);
        }
    }

    /**
     * @param OrderProductTextData $orderProductTextData
     * @return void
     */
    public function addRealizationFromOrderProductTextData(OrderProductTextData $orderProductTextData)
    {
        if($product = Product::getFromArticle($orderProductTextData->getArticle())){
            $this->addRealizations($product, $orderProductTextData->getPrice(), $orderProductTextData->getQuantity());
        }
    }

}