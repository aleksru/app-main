<?php

namespace App\Services\Statistic\Abstractions;

use App\Services\Statistic\GeneralStatistic\GeneralStatisticDBContract;
use App\Services\Statistic\QueryRepository;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


abstract class GeneralStatisticRepository implements IGeneralStatisticRepository
{
    /**
     * @var QueryRepository
     */
    protected $queryRepository;

    /**
     * @var Carbon
     */
    protected $dateFrom;

    /**
     * @var Carbon
     */
    protected $dateTo;

    /**
     * @var Collection|null
     */
    protected $avgSales;

    /**
     * GeneralStatisticRepository constructor.
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->queryRepository = new QueryRepository();
    }

    abstract protected function buildJoin(Builder $builder) : Builder;
    abstract protected function getTableName() : string;
    abstract public function getFieldName() : string;
    abstract protected function getGroupByFieldName(): string;

    public function getDoneCount() : Collection
    {
        $query = $this->buildJoin($this->queryRepository->getBaseSuccessRealizationsOnOrdersQuery($this->dateFrom, $this->dateTo));
        return $query->selectRaw($this->getSelection() . ", COUNT(orders.id) AS " . GeneralStatisticDBContract::DONE_COUNT)
            ->groupBy($this->getGroupBy())
            ->get();
    }

    public function getMissedCount() : Collection
    {
        $query = $this->buildJoin($this->queryRepository->getBaseRefusalRealizationsOnOrdersQuery($this->dateFrom, $this->dateTo));
        return $query->selectRaw($this->getSelection() . ', COUNT(orders.id) AS ' . GeneralStatisticDBContract::MISSED_COUNT)
            ->groupBy($this->getGroupBy())
            ->get();
    }

    public function getProfitByDates()
    {
        $query = $this->buildJoin($this->queryRepository->getBaseOrderSalesQuery($this->dateFrom, $this->dateTo));
        return $query->selectRaw($this->getSelection() .
                    ', (SUM(realizations.price) - SUM(realizations.price_opt)) AS ' . GeneralStatisticDBContract::PROFIT)
            ->groupBy($this->getGroupBy())
            ->get();
    }

    public function getAvgInvoice()
    {
        if($this->avgSales === null){
            $this->getAvgInvoiceProfit();
        }

        return $this->avgSales;
    }

    public function getAvgProfit()
    {
        if($this->avgSales === null){
            $this->getAvgInvoiceProfit();
        }

        return $this->avgSales;
    }

    public function getAvgAllInvoices()
    {
        $query = $this->buildJoin($this->queryRepository->getBaseOrderAllSalesQuery($this->dateFrom, $this->dateTo));
        $query->selectRaw($this->getSelection() . ', SUM(realizations.price) AS sum_order')
            ->groupBy($this->getGroupBy(), 'orders.id');
        return DB::table(DB::raw("({$query->toSql()}) as sales"))
            ->mergeBindings($query)
            ->selectRaw($this->getFieldName() . ', AVG(sum_order) AS ' . GeneralStatisticDBContract::AVG_ALL_INVOICES)
            ->groupBy($this->getFieldName())
            ->get();
    }

    protected function getAvgInvoiceProfit()
    {
        $query = $this->buildJoin($this->queryRepository->getBaseOrderSalesProfitQuery($this->dateFrom, $this->dateTo));
        $query->selectRaw($this->getSelection() . ', SUM(realizations.price) AS sum_order')
            ->groupBy($this->getGroupBy(), 'orders.id');
        $this->avgSales = DB::table(DB::raw("({$query->toSql()}) as sales"))
            ->mergeBindings($query)
            ->selectRaw($this->getFieldName() . ', AVG(sum_order) AS ' . GeneralStatisticDBContract::AVG_INVOICE .
                            ', AVG(profit) AS ' . GeneralStatisticDBContract::AVG_PROFIT)
            ->groupBy($this->getFieldName())
            ->get();
    }

    protected function getSelection(): string
    {
        return $this->getTableName() . '.' .$this->getFieldName();
    }

    protected function getGroupBy(): string
    {
        return $this->getTableName() . '.' . $this->getGroupByFieldName();
    }

    /**
     * @param Carbon $dateFrom
     */
    public function setDateFrom(Carbon $dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * @param Carbon $dateTo
     */
    public function setDateTo(Carbon $dateTo)
    {
        $this->dateTo = $dateTo;
    }
}