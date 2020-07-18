<?php


namespace App\Services\Statistic\TypeCreateOrder;

use App\Enums\TypeCreatedOrder;
use App\Repositories\OrderStatusRepository;
use App\Services\Statistic\Abstractions\BaseReportTableRender;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TypeCreateOrderStatistic extends BaseStatistic
{
    /**
     * @var Store|null
     */
    protected $store;

    /**
     * TypeCreateOrderStatistic constructor.
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @param Store|null $store
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo, ?Store $store = null)
    {
        parent::__construct($dateFrom, $dateTo);
        $this->store = $store;
    }

    static function createDataItem($field)
    {
        return new TypeCreateOrderItem($field);
    }

    public function generateAll()
    {
        $results = DB::table('orders')->selectRaw('orders.id, realizations.id as reliz_id, orders.created_at, `type_created_order`, products.product_name')
            ->join('realizations', 'orders.id', '=', 'realizations.order_id' )
            ->join('products', 'realizations.product_id', '=', 'products.id')
            ->whereDate('orders.created_at', '>=', $this->dateFrom)
            ->whereDate('orders.created_at', '<=', $this->dateTo)
            ->where('orders.status_id', app(OrderStatusRepository::class)->getIdsStatusConfirmed())
            ->whereNull('realizations.deleted_at');
        if( $this->store !== null ){
            $results->where('orders.store_id', $this->store->id);
        }
        $results = $results->get();
        foreach ($results as $result){
            $field = $this->getOrCreateFieldOnContainer($result->id . '_' .$result->product_name . '_' . $result->reliz_id);
            $field->setOrderId($result->id);
            $field->setProduct($result->product_name);
            $field->setType($result->type_created_order === null ? TypeCreatedOrder::CUSTOM : $result->type_created_order);
            $field->setCreatedAt(Carbon::parse($result->created_at));
        }
    }

    public static function createTableRender(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null): BaseReportTableRender
    {
        return new TypeCreateOrderTableRender($routeIndex, $routeDatatable, $labelHeader, $name);
    }

    public static function createDefaultTableRender(): BaseReportTableRender
    {
        return self::createTableRender(
            route('statistic.type_create_orders'),
            route('statistic.type_create_orders.table'),
        'Типы создания заказов'
        );
    }
}
