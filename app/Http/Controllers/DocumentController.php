<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Service\DocumentBuilder\Builder;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\DataDocs\MarketCheckData;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\DataDocs\Report;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\DataDocs\RouteMapData;
use App\Models\Courier;
use App\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ReportRepository;
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
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getMarketCheck(Order $order)
    {
        if ($order->realizations()->count() === 0) {
            return redirect()->back()->with(['error' => 'В заказе отсутствуют товары!']);
        }

        $this->builder->download(new MarketCheckData($order), 'invoice');
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

        $this->builder->download(new RouteMapData($courier, $request->get('date')), 'route_map');
    }

    /**
     * Отчет по заказам
     *
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reportDayOrders(Request $request, OrderRepository $orderRepository, ReportRepository $reportRepository)
    {
        $orders = $orderRepository->getOrdersToDateWithRelations($request->get('date'), $request->get('date_end'));
        $reportRepository->getAllOperatorsReport($orders);
        debug($reportRepository->getAllOperatorsReport($orders));
        return 1;
//
//        if($orders->isEmpty()) {
//            return redirect()->back()->with(['error' => 'Отсутствуют заказы за выбранный период!']);
//        }
        $this->builder->getReportDays($request->get('date'), $request->get('date_end'));
//
        $this->builder->download(new Report($orders), 'day_report');


//        PhpExcelTemplator::outputToFile(
//            storage_path('app'.DIRECTORY_SEPARATOR.'exel_templates'.DIRECTORY_SEPARATOR) . 'every_day_report.xlsx',
//            storage_path('app'.DIRECTORY_SEPARATOR.'exel_templates'.DIRECTORY_SEPARATOR) . 'every_day_report_success.xlsx',
//            [
//                '{day}' => date('d-m-Y'),
//                '{month}' => 'Sales department',
//                '[operator]' => [1,2,3],
//                '[store]' => [9,8,7,6]
//        ]
//        );
        //return 1;
    }
}