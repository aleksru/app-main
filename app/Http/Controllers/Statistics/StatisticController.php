<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Controller;
use App\Order;
use App\Repositories\StatisticRepository;

class StatisticController extends Controller
{
    /**
     * @var StatisticRepository
     */
    protected $statisticRepository;

    /**
     * StatisticController constructor.
     * @param StatisticRepository $statisticRepository
     */
    public function __construct(StatisticRepository $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function index()
    {
        $data = $this->statisticRepository->getOrdersForMonth();
        debug($data);
        return view('front.statistic.index', ['data' => $data]);
    }
}