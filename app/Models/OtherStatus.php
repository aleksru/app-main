<?php

namespace App\Models;

use App\Enums\OtherStatusEnums;
use App\Enums\StatusResults;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OtherStatus extends Model
{
    protected $guarded = ['id'];

    /**
     * Подстатус
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeTypeSubStatuses($query)
    {
        return $query->where('type', OtherStatusEnums::SUBSTATUS_TYPE);
    }

    /**
     * Статус склад
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeTypeStockStatuses($query)
    {
        return $query->where('type', OtherStatusEnums::STOCK_TYPE);
    }

    /**
     * Статус логистика
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeTypeLogisticStatuses($query)
    {
        return $query->where('type', OtherStatusEnums::LOGISTIC_TYPE);
    }

    /**
     * Статус реализации
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeTypeRealizationStatuses($query)
    {
        return $query->where('type', OtherStatusEnums::REALIZATION_STATUS_TYPE);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeTypeRealizationStatusSuccess($query)
    {
        return $query->typeRealizationStatuses()->where('result', StatusResults::SUCCESS);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeTypeRealizationStatusRefusal($query)
    {
        return $query->typeRealizationStatuses()->where('result', StatusResults::REFUSAL);
    }
}
