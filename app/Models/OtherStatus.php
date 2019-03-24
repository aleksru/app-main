<?php

namespace App\Models;

use App\Enums\OtherStatusEnums;
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
}
