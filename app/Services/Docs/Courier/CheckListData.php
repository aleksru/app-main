<?php


namespace App\Services\Docs\Courier;


use App\Models\Courier;
use App\Models\Realization;
use Carbon\Carbon;

class CheckListData
{
    private $courier;
    private $dateDelivery;
    private $corporateInfo;
    private $realizations = [];

    /**
     * @return Courier
     */
    public function getCourier() : Courier
    {
        return $this->courier;
    }

    /**
     * @param Courier $courier
     */
    public function setCourier(Courier $courier)
    {
        $this->courier = $courier;
    }

    /**
     * @return Carbon
     */
    public function getStrDateDelivery()
    {
        return $this->dateDelivery->format('d.m.Y');
    }

    /**
     * @return Carbon
     */
    public function getDateDelivery() : Carbon
    {
        return $this->dateDelivery;
    }

    /**
     * @param Carbon $dateDelivery
     */
    public function setDateDelivery(Carbon $dateDelivery)
    {
        $this->dateDelivery = $dateDelivery;
    }

    /**
     * @return mixed
     */
    public function getCorporateInfo()
    {
        return $this->corporateInfo;
    }

    /**
     * @param mixed $corporateInfo
     */
    public function setCorporateInfo($corporateInfo)
    {
        $this->corporateInfo = $corporateInfo;
    }

    /**
     * @return array
     */
    public function getRealizations() : array
    {
        return $this->realizations;
    }

    /**
     * @param array $realizations
     */
    public function setRealizations(array $realizations)
    {
        foreach ($realizations as $realization){
            if( ! ($realization instanceof Realization) ){
                throw new \InvalidArgumentException('Realization not instance App\Models\Realization!');
            }
        }
        $this->realizations = $realizations;
    }

}