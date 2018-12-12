<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Service\DocumentBuilder\Builder;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\MarketCheckData;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\RouteMap;
use App\Models\Courier;
use App\Order;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * DocumentController constructor.
     * @param Builder $builder
     */
    public function __construct (Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getMarketCheck(Order $order)
    {
        if ($order->products->isEmpty()) {
            return redirect()->back()->with(['error' => 'В заказе отсутствуют товары!']);
        }

        $this->builder->download(new MarketCheckData($order), 'invoice');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.print-form.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form(Request $request)
    {
        $dateDelivery = $request->get('date_delivery');
        $courier = Courier::find($request->get('courier'));
        $courier = $courier ? $courier->load(['orders' => function($query) use ($dateDelivery){
            $query->deliveryToday($dateDelivery);
        }]) : null;

        return view('front.print-form.index', ['courier' => $courier, 'toDate' => $dateDelivery]);
    }

    /**
     * Маршрутный лист курьера
     *
     * @param Courier $courier
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRouteMap(Courier $courier, Request $request)
    {
        if ($courier->orders()->deliveryToday($request->get('date'))->count() === 0 ) {
            return redirect()->back()->with(['error' => 'На курьера не назначено заказов!']);
        }

        $this->builder->download(new RouteMap($courier, $request->get('date')), 'route_map');
    }

    /**
     * Отчет по заказам
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reportDayOrders(Request $request)
    {
        if (Order::toDay($request->get('date'))->count() === 0 ) {
            return redirect()->back()->with(['error' => 'Отсутствуют заказы за выбранный период!']);
        }

        $this->builder->download(new Report($request->get('date')), 'day_report');
    }
}