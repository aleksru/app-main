<?php


namespace App\Services\Statistic\Abstractions;


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
        $this->name = $name ? $name : $this->randomString();
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

    private function randomString()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randString = '';
        for ($i = 0; $i < 7; $i++) {
            $randString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randString;
    }
}