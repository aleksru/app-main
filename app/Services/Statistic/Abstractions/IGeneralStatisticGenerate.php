<?php


namespace App\Services\Statistic\Abstractions;


interface IGeneralStatisticGenerate
{
    public function genDone();

    public function genMissed();

    public function genProfit();

    public function genAvgInvoice();

    public function genAvgMainInvoice();

    public function genAvgProfit();

    public function getSumSales();

    public function generateGenerals();
}