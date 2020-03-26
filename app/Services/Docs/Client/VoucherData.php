<?php


namespace App\Services\Docs\Client;


use App\Models\Realization;
use Carbon\Carbon;

class VoucherData
{
    private $numberOrder;
    /**
     * @var Carbon
     */
    private $dateDelivery;
    private $corporateInfo;
    private $clientInfo;
    private $realizations = [];
    private $warrantyText;

    /**
     * @return mixed
     */
    public function getWarrantyText()
    {
        return $this->warrantyText;
    }

    /**
     * @param mixed $warrantyText
     */
    public function setWarrantyText(string $warrantyText)
    {
        $this->warrantyText = $warrantyText;
    }

    /**
     * @return int
     */
    public function getNumberOrder() : int
    {
        return $this->numberOrder;
    }

    /**
     * @param mixed $numberOrder
     */
    public function setNumberOrder(int $numberOrder)
    {
        $this->numberOrder = $numberOrder;
    }

    /**
     * @return string
     */
    public function getDateDelivery() :string
    {
        return $this->dateDelivery->format('d.m.Y');
    }

    /**
     * @param Carbon $dateDelivery
     */
    public function setDateDelivery(Carbon $dateDelivery)
    {
        $this->dateDelivery = $dateDelivery;
    }

    /**
     * @return string
     */
    public function getCorporateInfo() : string
    {
        return $this->corporateInfo;
    }

    /**
     * @param string $corporateInfo
     */
    public function setCorporateInfo(string $corporateInfo)
    {
        $this->corporateInfo = $corporateInfo;
    }

    /**
     * @return string
     */
    public function getClientInfo() : string
    {
        return $this->clientInfo;
    }

    /**
     * @param mixed $clientInfo
     */
    public function setClientInfo(string $clientInfo)
    {
        $this->clientInfo = $clientInfo;
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

    /**
     * @param Realization $realization
     */
    public function addRealization(Realization $realization)
    {
        $this->realizations[] = $realization;
    }

    public function getFullSumRealizations()
    {
        return array_reduce($this->realizations, function ($prev, $curr){
            return $prev + $curr->price * $curr->quantity;
        }, 0);
    }


}