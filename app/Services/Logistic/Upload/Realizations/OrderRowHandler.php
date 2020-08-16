<?php


namespace App\Services\Logistic\Upload\Realizations;


use App\Exceptions\Orders\OrderNotFoundException;
use App\Exceptions\Orders\OrderUpdateFromRowException;
use App\Exceptions\Realizations\RealizationsUpdateFromRowException;
use App\Exceptions\Realizations\Upload\ErrorUpdateEntityException;
use App\Models\Realization;
use App\Order;
use App\Product;
use App\Services\Logistic\Upload\Realizations\Data\Row;
use App\Services\Logistic\Upload\Realizations\Interfaces\RowHandlerImp;
use App\Services\Logistic\Upload\Realizations\Updaters\AbstractUpdateFromRow;
use App\Services\Logistic\Upload\Realizations\Updaters\UpdateOrderFromRow;
use App\Services\Logistic\Upload\Realizations\Updaters\UpdateRealizationFromRow;

class OrderRowHandler implements RowHandlerImp
{
    /**
     * @var int
     */
    public static $PERCENT_SIMILAR_TEXT = 80;

    /**
     * @var Row
     */
    protected $row;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var UpdateRealizationFromRow
     */
    protected $realizationsUpdater;

    /**
     * @var UpdateOrderFromRow
     */
    protected $orderUpdater;

    /**
     * OrderRowHandler constructor.
     */
    public function __construct()
    {
        $this->realizationsUpdater = new UpdateRealizationFromRow();
        $this->orderUpdater = new UpdateOrderFromRow();
    }


    /**
     * @param Row $row
     * @throws OrderNotFoundException
     */
    public function setRow(Row $row): void
    {
        $this->row = $row;
        $this->realizationsUpdater->setRow($row);
        $this->orderUpdater->setRow($row);
        if($this->order === null || $this->order->id !== $this->row->getOrderId()){
            $this->findOrder($row->getOrderId());
        }
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
        $this->orderUpdater->serOrder($this->order);
    }

    /**
     * @throws ErrorUpdateEntityException
     */
    public function handle()
    {
        foreach ($realizations = $this->order->realizations as $key => $realization){
            if($this->hasRowRealization($realization)){
                $this->realizationsUpdater->setRealization($realizations->pull($key));
                $this->update($this->realizationsUpdater);
                break;
            }
        }
        $this->update($this->orderUpdater);
    }

    /**
     * @param AbstractUpdateFromRow $abstractUpdateFromRow
     * @throws ErrorUpdateEntityException
     */
    public function update(AbstractUpdateFromRow $abstractUpdateFromRow)
    {
        try {
            $abstractUpdateFromRow->update();
        }catch (OrderUpdateFromRowException $exception){
            throw new ErrorUpdateEntityException($exception->getMessage());
        }catch (RealizationsUpdateFromRowException $exception){
            throw new ErrorUpdateEntityException($exception->getMessage());
        }
    }

    /**
     * @param int $orderID
     * @return Order
     * @throws OrderNotFoundException
     */
    protected function findOrder(int $orderID): Order
    {
        $order = Order::with('realizations.anyProduct')->find($orderID);
        if( ! $order ){
            throw new OrderNotFoundException("OrderRowHandler: Order #{$orderID} not found!");
        }
        $this->setOrder($order);

        return $this->order;
    }

    private function hasRowRealization(Realization $realization): bool
    {
        if( (int)($realization->price) !== (int)($this->row->getProductPrice()) ){
            return false;
        }
        if( ! $this->checkNamesProducts($realization->anyProduct) ){
            return false;
        }

        return true;
    }


    private function checkNamesProducts(Product $product): bool
    {
        $strProductName = mb_strtolower(str_replace(' ', '', $product->product_name));
        $strRowProductName = mb_strtolower(str_replace(' ', '', $this->row->getProductName()));
        similar_text($strProductName, $strRowProductName, $p);

        return self::$PERCENT_SIMILAR_TEXT < ceil($p);
    }
}
