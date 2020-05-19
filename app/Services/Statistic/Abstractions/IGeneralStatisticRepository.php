<?php


namespace App\Services\Statistic\Abstractions;


interface IGeneralStatisticRepository
{
    function getFieldName() : string;

    function getDoneCount();

    function getMissedCount();

    function getProfitByDates();

    function getAvgInvoice();

    function getAvgProfit();

    function getAvgAllInvoices();
}