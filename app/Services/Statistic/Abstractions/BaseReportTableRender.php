<?php


namespace App\Services\Statistic\Abstractions;


use Illuminate\Support\Str;

class BaseReportTableRender
{
    protected $routeIndex;
    protected $routeDatatable;
    protected $name;
    protected $labelHeader;

    /**
     * DataTableRender constructor.
     * @param string $routeIndex
     * @param string $routeDatatable
     * @param string|null $labelHeader
     * @param string|null $name
     */
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        $this->routeIndex = $routeIndex;
        $this->routeDatatable = $routeDatatable;
        $this->name = $name ? $name : Str::random(7);
        $this->labelHeader = $labelHeader;
    }

    public function getColumns() : array
    {
        return [];
    }

    public function renderTable()
    {
        return view('statistic.base.table', ['render' => $this]);
    }

    public function renderSingleReport()
    {
        return view('statistic.base.single_report', ['DTRender' => $this]);
    }

    /**
     * @return string
     */
    public function getRouteIndex(): string
    {
        return $this->routeIndex;
    }

    /**
     * @return string
     */
    public function getRouteDatatable(): string
    {
        return $this->routeDatatable;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getLabelHeader()
    {
        return $this->labelHeader;
    }
}